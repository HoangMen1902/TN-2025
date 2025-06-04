<?php

namespace Modules\PaymentModule\Livewire\Components;

use Livewire\Component;
use App\Models\Voucher;
use Illuminate\Support\Facades\Session;

class Summary extends Component
{
    public $carts;
    public $voucherCode = '';
    public $voucherMessage = '';
    public $voucherDiscount = 0;
    public $availableVouchers;
    public function mount()
    {
        $this->voucherCode = session('voucher_code', '');
        $this->voucherDiscount = session('voucher_discount', 0);
        $this->availableVouchers = Voucher::where('voucher_status', 'active')
            ->get();
    }

    public function applyVoucher()
    {
        $totalPrice = 0;
        foreach ($this->carts as $cart) {
            $price = $cart->sku->sale_price ?? $cart->combo->sale_price ?? 0;
            $totalPrice += $cart->quantity * $price;
        }

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

        session([
            'voucher_code' => $this->voucherCode,
            'voucher_discount' => $discount,
        ]);
    }
    public function render()
    {
        return view('paymentmodule::livewire.components.summary');
    }
}
