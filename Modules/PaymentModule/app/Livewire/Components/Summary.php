<?php

namespace Modules\PaymentModule\Livewire\Components;

use App\Models\FlashsaleProduct;
use Livewire\Component;
use App\Models\Voucher;
use App\Models\VoucherUsed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;


class Summary extends Component
{
    public $carts;
    public $voucherCode = '';
    public $voucherMessage = '';
    public $voucherDiscount = 0;
    public $availableVouchers;

    public $shipping_fee = 0;
    public $original_shipping_fee = 0;
    public $finalPrice;
    public $flashsale_products = [];
    public $originalPrice = 0;
    public $voucher_applied_code = '';
    public int $voucher_applied_id;
    public bool $applied_voucher = false;

    public bool $is_first = true;
    public $submitable = false;


    #[On('update-submit')]
    public function updateSubmit()
    {
        $this->submitable = true;
    }


    #[On('updated_selected_unit')]
    public function updatePrice($fee)
    {
        if ($fee === null) {
            return;
        }
        Log::info($this->finalPrice);
        $this->finalPrice  -= $this->shipping_fee;
        Log::info($this->finalPrice);

        $this->shipping_fee = $fee;
        $this->original_shipping_fee = $this->shipping_fee;
        $this->finalPrice += $this->shipping_fee;
        Log::info($this->finalPrice);

        if ($this->is_first || !$this->applied_voucher) {
            $this->is_first = false;
        } else {
            $this->dispatch('apply-voucher');
        }
        session()->put('order_total', $this->finalPrice);
        $this->dispatch('update-submit');
    }


    public function checkFlashsale(): array
    {
        $item_arr = [];
        foreach ($this->carts as $cart) {
            if ($cart->item_type === "sku") {
                $item_arr[] = $cart->sku_id;
            }
        }

        $now = Carbon::now();
        $flashsales = FlashsaleProduct::whereIn('sku_id', $item_arr)->with('flashsale', function ($q) use ($now) {
            $q->where('started_at', '<=', $now)
                ->where('expired_at', '>=', $now);
        })->get();

        $flashsaleMap = $flashsales->filter(function ($item) {
            return $item->flashsale !== null;
        })->mapWithKeys(function ($item) {
            return [
                "$item->sku_id" => [
                    'discount_type' => $item->flashsale->discount_type,
                    'discount_amount' => $item->flashsale->discount_amount
                ]
            ];
        })->toArray();
        $this->flashsale_products = $flashsaleMap;

        return $flashsaleMap;
    }

    public function mount()
    {
        Session::forget('decrease_amount');
        $product_in_flashsale = $this->checkFlashsale();

        foreach ($this->carts as $index => $cart) {
            if ($cart->item_type === 'combo') {
                $this->originalPrice += $cart->combo->sale_price * $cart->quantity;
            } elseif ($cart->item_type === 'sku') {
                $unit_price = $cart->sku->sale_price ?? $cart->sku->price;

                if (isset($product_in_flashsale[$cart->sku_id])) {
                    $discount_type = $product_in_flashsale[$cart->sku_id]['discount_type'];
                    $discount_amount = $product_in_flashsale[$cart->sku_id]['discount_amount'];

                    if ($discount_type === 'percent') {
                        $unit_price -= ($unit_price * $discount_amount / 100);
                    } elseif ($discount_type === 'specific') {
                        $unit_price -= $discount_amount;
                    }

                    if ($unit_price < 0) {
                        $unit_price = 0;
                    }
                }

                $this->originalPrice += $cart->quantity * $unit_price;
            }
        }
        $this->finalPrice = $this->originalPrice;


        $this->availableVouchers = Voucher::select('*')
            ->selectRaw("
        CASE 
            WHEN voucher_scope = 'shipping' AND voucher_type = 'percent'
                 THEN LEAST((? * reduced_amount / 100), max_discount_amount)
            WHEN voucher_scope = 'shipping' AND voucher_type = 'amount'
                 THEN LEAST(reduced_amount, ?)

            WHEN voucher_scope = 'global' AND voucher_type = 'percent'
                 THEN LEAST((? * reduced_amount / 100), max_discount_amount)
            WHEN voucher_scope = 'global' AND voucher_type = 'amount'
                 THEN LEAST(reduced_amount, ?)

            ELSE 0
        END as discount_value
    ", [
                $this->shipping_fee,
                $this->shipping_fee,
                $this->finalPrice,
                $this->finalPrice,
            ])
            ->where('voucher_status', 'active')
            ->where('start_at', '<', now())
            ->where('expired_at', '>', now())
            ->whereHas('voucherUsed', function ($q) {
                $q->where('is_used', false);
            })
            ->orderBy('discount_value', 'DESC')
            ->get();
    }

    #[On('apply-voucher')]
    public function applyVoucher($is_chosen = false)
    {

        // if($is_chosen) {
        $this->finalPrice = $this->originalPrice;
        // } else {
        //     return;
        // }
        $this->shipping_fee = $this->original_shipping_fee;

        $totalPrice = $this->originalPrice;
        if (!$this->voucherCode) {
            return;
        }

        $voucher = Voucher::where('voucher_code', $this->voucherCode)
            ->where('voucher_status', 'active')
            ->where('expired_at', '>', now())
            ->first();

        if (!$voucher) {
            $this->voucherMessage = 'Mã giảm giá không hợp lệ hoặc đã hết hạn.';
            $this->voucherDiscount = 0;
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;
            $this->applied_voucher = false;
            session()->put('order_total', $this->finalPrice);
            return;
        }
        // Check xem user đã dùng voucher này chưa
        $user_voucher = VoucherUsed::where('voucher_id', $voucher->id)
            ->where('user_id', Auth::id())
            ->where('is_used', true)
            ->first();

        if ($user_voucher) {
            $this->voucherMessage = 'Bạn đã sử dụng mã giảm giá này rồi.';
            $this->voucherDiscount = 0;
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;
            $this->applied_voucher = false;
            session()->put('order_total', $this->finalPrice);
            return;
        }

        if ($totalPrice < $voucher->requirement_price) {
            $this->voucherMessage = 'Đơn hàng chưa đủ điều kiện áp dụng mã.';
            $this->applied_voucher = false;
            $this->voucherDiscount = 0;
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;

            session()->put('order_total', $this->finalPrice);
            return;
        }
        $voucher_type = $voucher->voucher_scope;
        $voucher_max_amount = $voucher->max_discount_amount;


        if ($voucher_type === "shipping") {
            if ($voucher->voucher_type === "percent") {

                if ($this->shipping_fee <= 0 && !$this->applied_voucher) {
                    $this->voucherMessage = 'Vui lòng chọn đơn vị vận chuyển.';
                    return;
                }

                $discountAmount = $this->shipping_fee * ($voucher->reduced_amount / 100);
                if ($discountAmount > $voucher_max_amount) {
                    $discountAmount = $voucher_max_amount;
                }
                $this->shipping_fee = ($this->shipping_fee - $discountAmount) < 0 ? 0 : ($this->shipping_fee - $discountAmount);
                $this->finalPrice += $this->shipping_fee;
                $this->applied_voucher = true;
            } elseif ($voucher->voucher_type === "amount") {

                if ($this->shipping_fee <= 0 && !$this->applied_voucher) {
                    $this->voucherMessage = 'Vui lòng chọn đơn vị vận chuyển.';
                    return;
                }
                $discountAmount = ($voucher->reduced_amount);
                $this->shipping_fee = ($this->shipping_fee - $discountAmount) < 0 ? 0 : ($this->shipping_fee - $discountAmount);
                $this->finalPrice += $this->shipping_fee;
                $this->applied_voucher = true;
            }

            Session::put('shipping_fee', $this->shipping_fee);
            $this->voucherDiscount = $discountAmount;
        }

        if ($voucher_type === 'global') {
            if ($voucher->voucher_type === "percent") {
                $discountAmount = $this->finalPrice * ($voucher->reduced_amount / 100);
                Log::info('Discount Amount 1: ' . $discountAmount);
                if ($discountAmount > $voucher_max_amount) {
                    $discountAmount = $voucher_max_amount;
                    Log::info('Discount Amount 2: ' . $discountAmount);
                }
                Log::info('Discount Amount 3: ' . $discountAmount);
                $this->finalPrice = max(0, $this->finalPrice - $discountAmount);
            } elseif ($voucher->voucher_type === 'amount') {
                $discountAmount = $voucher->reduced_amount;

                $this->finalPrice = max(0, $this->finalPrice - $discountAmount);
            }

            $this->voucherDiscount = $discountAmount;
            $this->applied_voucher = true;
        }
        $this->voucherMessage = 'Đã áp dụng mã giảm giá thành công!';
        session()->put('order_total', $this->finalPrice);
        session()->put('decrease_amount', $discountAmount);
    }

    public function render()
    {
        return view('paymentmodule::livewire.components.summary');
    }
}
