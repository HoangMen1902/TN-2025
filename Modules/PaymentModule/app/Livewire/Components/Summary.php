<?php

namespace Modules\PaymentModule\Livewire\Components;

use App\Models\FlashsaleProduct;
use Livewire\Component;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class Summary extends Component
{
    public $carts;
    public $voucherCode = '';
    public $voucherMessage = '';
    public $voucherDiscount = 0;
    public $availableVouchers;

    public $shipping_fee = 0;
    public $finalPrice;
    public $flashsale_products = [];
    public $originalPrice = 0;



    #[On('updated_selected_unit')]


    public function updatePrice($fee)
    {
        if ($fee === null) {
            return;
        }
        $this->finalPrice  -= $this->shipping_fee;
        $this->shipping_fee = $fee;
        $this->finalPrice += $this->shipping_fee;
        session()->put('order_total', $this->finalPrice);
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

        $flashsaleMap = $flashsales->mapWithKeys(function ($item) {
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

        $this->voucherCode = session('voucher_code', '');
        $this->voucherDiscount = session('voucher_discount', 0);
        $this->availableVouchers = Voucher::where('voucher_status', 'active')
            ->get();
        session([
            'finalPrice' => $this->finalPrice
        ]);
    }

    public function applyVoucher()
    {
        $totalPrice = $this->originalPrice;

        $voucher = Voucher::where('voucher_code', $this->voucherCode)
            ->where('voucher_status', 'active')
            ->where('expired_at', '>', now())
            ->first();

        if (!$voucher) {
            $this->voucherMessage = 'Mã giảm giá không hợp lệ hoặc đã hết hạn.';
            $this->voucherDiscount = 0;
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;

            Session::forget(['voucher_code', 'voucher_discount']);
            session()->put('order_total', $this->finalPrice);
            return;
        }

        if ($totalPrice < $voucher->requirement_price) {
            $this->voucherMessage = 'Đơn hàng chưa đủ điều kiện áp dụng mã.';
            $this->voucherDiscount = 0;
            $this->finalPrice = $this->originalPrice + $this->shipping_fee;

            Session::forget(['voucher_code', 'voucher_discount']);
            session()->put('order_total', $this->finalPrice);
            return;
        }

        $discount = $voucher->voucher_type === 'percent'
            ? $totalPrice * $voucher->reduced_amount / 100
            : $voucher->reduced_amount;

        $this->voucherDiscount = $discount;
        $this->voucherMessage = 'Áp dụng mã giảm giá thành công!';
        $this->finalPrice = $this->originalPrice - $this->voucherDiscount + $this->shipping_fee;
        session([
            'voucher_code' => $this->voucherCode,
            'voucher_discount' => $discount,
            'finalPrice' => $this->finalPrice
        ]);
        session()->put('order_total', $this->finalPrice);
    }

    public function render()
    {
        return view('paymentmodule::livewire.components.summary');
    }
}
