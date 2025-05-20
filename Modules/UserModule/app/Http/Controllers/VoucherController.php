<?php

namespace Modules\UserModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $vouchers = Voucher::where('expired_at', '>=', now())
            ->where('voucher_status', 'active')
            ->get()
            ->map(function ($voucher) use ($user) {
                $isUsed = $user ? $voucher->voucherUsed()->where('user_id', $user->id)->exists() : false;
                return [
                    'id' => $voucher->id,
                    'category' => $voucher->voucher_type === 'percent' ? 'Phần trăm' : 'Cố định', 
                    'discount' => $voucher->voucher_type === 'percent'
                        ? "Giảm {$voucher->reduced_amount}%"
                        : "Giảm " . number_format($voucher->reduced_amount, 0) . " ₫",
                    'condition' => "Cho đơn hàng từ " . number_format($voucher->requirement_price, 0) . " ₫",
                    'code' => $voucher->voucher_name,
                    'terms' => "Điều kiện áp dụng: áp dụng cho đơn hàng từ " . number_format($voucher->requirement_price, 0) . " ₫. Không áp dụng cho một số sản phẩm đặc biệt.", // Có thể thêm cột terms vào DB
                    'image' => 'https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/ico_coupongreen.svg?q=11027',
                    'expiry' => $voucher->expired_at->format('d/m/Y'),
                    'is_used' => $isUsed,
                ];
            });

        // Cập nhật tabs cho phù hợp với voucher_type
        $tabs = [
            'percent' => 'Phần trăm',
            'amount' => 'Cố định',
        ];

        return view('usermodule::profile.voucher', compact('vouchers', 'tabs'));
    }
}
