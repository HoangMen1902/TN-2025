<?php

namespace Modules\PaymentModule\Livewire\Components;

use Livewire\Component;
use App\Models\Voucher;
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
    public $originalPrice = 0;


    
    #[On('updated_selected_unit')] 


    public function updatePrice($fee) {
        if($fee === null) {
            return;
        }
        $this->finalPrice  -= $this->shipping_fee;
        $this->shipping_fee = $fee;
        $this->finalPrice += $this->shipping_fee;
        session()->put('order_total', $this->finalPrice);
    }
    public function mount()
    {
        foreach ($this->carts as $index => $cart) {
            if($cart->item_type === 'combo') {
                $this->originalPrice += $cart->combo->sale_price * $cart->quantity;
            } elseif ($cart->item_type === 'sku') {
                $this->originalPrice += $cart->quantity * $cart->sku->sale_price;
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

        $voucher = Voucher::where('voucher_name', $this->voucherCode)
            ->where('voucher_status', 'active')
            ->where('expired_at', '>', now())
            ->first();

        if (!$voucher) {
            $this->voucherMessage = 'Mã giảm giá không hợp lệ hoặc đã hết hạn.';
            $this->voucherDiscount = 0;
            Session::forget(['voucher_code', 'voucher_discount']);
            return;
        }

        if ($totalPrice < $voucher->requirement_price) {
            $this->voucherMessage = 'Đơn hàng chưa đủ điều kiện áp dụng mã.';
            $this->voucherDiscount = 0;
            Session::forget(['voucher_code', 'voucher_discount']);
            return;
        }

        $discount = $voucher->voucher_type === 'percent'
            ? $totalPrice * $voucher->reduced_amount / 100
            : $voucher->reduced_amount;

        $this->voucherDiscount = $discount;
        $this->voucherMessage = 'Áp dụng mã giảm giá thành công!';

        $this->finalPrice = $this->originalPrice - $this->voucherDiscount;
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
