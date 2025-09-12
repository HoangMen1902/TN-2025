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
            ->where('requirement_price', '<', $this->finalPrice)
            ->orderBy('discount_value', 'DESC')
            ->get();
    }

    #[On('apply-voucher')]
    public function applyVoucher($is_chosen = false)
    {
        $this->finalPrice    = $this->originalPrice;
        $this->shipping_fee  = $this->original_shipping_fee;
        $this->voucherDiscount = 0;
        $this->applied_voucher = false;

        if (!$this->voucherCode) {
            return;
        }

        $voucher = Voucher::where('voucher_code', $this->voucherCode)
            ->where('voucher_status', 'active')
            ->where('expired_at', '>', now())
            ->first();

        if (!$voucher) {
            $this->voucherMessage = 'Mã giảm giá không hợp lệ hoặc đã hết hạn.';
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;
            session()->put('order_total', $this->finalPrice);
            return;
        }

        $user_voucher = VoucherUsed::where('voucher_id', $voucher->id)
            ->where('user_id', Auth::id())
            ->where('is_used', true)
            ->first();

        if ($user_voucher) {
            $this->voucherMessage = 'Bạn đã sử dụng mã giảm giá này rồi.';
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;
            session()->put('order_total', $this->finalPrice);
            return;
        }

        if ($this->originalPrice < $voucher->requirement_price) {
            $this->voucherMessage = 'Đơn hàng chưa đủ điều kiện áp dụng mã.';
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;
            session()->put('order_total', $this->finalPrice);
            return;
        }


        $discountAmount = 0;
        $voucher_max_amount = $voucher->max_discount_amount;
        $voucher_type = $voucher->voucher_scope;

        if ($voucher_type === "shipping") {
            Log::info("=== Áp dụng voucher SHIPPING ===");
            Log::info("Phí ship ban đầu: {$this->shipping_fee}");

            if ($this->shipping_fee <= 0) {
                $this->voucherMessage = 'Vui lòng chọn đơn vị vận chuyển.';
                $this->finalPrice = $this->originalPrice + $this->shipping_fee;
                Log::info("Phí ship = 0, không áp dụng voucher. Tổng tiền: {$this->finalPrice}");
                return;
            }

            if ($voucher->voucher_type === "percent") {
                $discountAmount = $this->shipping_fee * ($voucher->reduced_amount / 100);
                Log::info("Giảm theo %: {$voucher->reduced_amount}% → {$discountAmount}");
            } else { // amount
                $discountAmount = $voucher->reduced_amount;
                Log::info("Giảm theo số tiền cố định: {$discountAmount}");
            }

            if ($discountAmount > $voucher_max_amount) {
                Log::info("Giảm vượt mức tối đa {$voucher_max_amount}, gán lại.");
                $discountAmount = $voucher_max_amount;
            }

            $this->shipping_fee = max(0, $this->shipping_fee - $discountAmount);
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;

            Log::info("Phí ship sau giảm: {$this->shipping_fee}");
            Log::info("Tổng cuối cùng: {$this->finalPrice}");
        }

        if ($voucher_type === "global") {
            Log::info("=== Áp dụng voucher GLOBAL ===");
            $totalBeforeDiscount = $this->originalPrice + $this->shipping_fee;
            Log::info("Tổng trước giảm: {$totalBeforeDiscount}");

            if ($voucher->voucher_type === "percent") {
                $discountAmount = $totalBeforeDiscount * ($voucher->reduced_amount / 100);
                Log::info("Giảm theo %: {$voucher->reduced_amount}% → {$discountAmount}");
            } else { 
                $discountAmount = $voucher->reduced_amount;
                Log::info("Giảm theo số tiền cố định: {$discountAmount}");
            }

            if ($discountAmount > $voucher_max_amount && $voucher->voucher_type === "percent") {
                Log::info("Giảm vượt mức tối đa {$voucher_max_amount}, gán lại.");
                $discountAmount = $voucher_max_amount;
            }

            $this->finalPrice = max(0, $totalBeforeDiscount - $discountAmount);

            Log::info("Giảm cuối cùng: {$discountAmount}");
            Log::info("Tổng sau giảm: {$this->finalPrice}");
        }
        $this->voucherDiscount = $discountAmount;
        $this->applied_voucher = true;
        $this->voucherMessage  = 'Đã áp dụng mã giảm giá thành công!';

        session()->put('order_total', $this->finalPrice);
        session()->put('decrease_amount', $discountAmount);
        Session::put('shipping_fee', $this->shipping_fee);
    }
    public function render()
    {
        return view('paymentmodule::livewire.components.summary');
    }
}
