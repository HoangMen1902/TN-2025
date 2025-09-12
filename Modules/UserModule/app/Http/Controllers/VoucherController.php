<?php

namespace Modules\UserModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;
use App\Models\VoucherUsed;
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $voucherUsed = VoucherUsed::with('voucher')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        $vouchers = $voucherUsed
            ->filter(function ($used) {
                $voucher = $used->voucher;

                if (!$voucher) return false;

                // Nếu có ngày hết hạn và ngày đó đã qua thì loại bỏ
                if ($voucher->expired_at && Carbon::parse($voucher->expired_at)->isPast()) {
                    return false;
                }

                return true;
            })
            ->groupBy(function ($used) {
                return $used->voucher->voucher_code ?? uniqid();
            })
            ->map(function ($group) {
                $used = $group->first();
                $voucher = $used->voucher;
                if (!$voucher) return null;

                return [
                    'id' => $voucher->id,
                    'name' => $voucher->voucher_name,
                    'category' => $voucher->voucher_type === 'percent' ? 'Phần trăm' : 'Cố định',
                    'discount' => $voucher->voucher_type === 'percent'
                        ? "Giảm {$voucher->reduced_amount}%"
                        : "Giảm " . number_format($voucher->reduced_amount, 0) . " ₫",
                    'condition' => "Cho đơn hàng từ " . number_format($voucher->requirement_price, 0) . " ₫",
                    'code' => $voucher->voucher_code,
                    'terms' => "Điều kiện áp dụng: áp dụng cho đơn hàng từ " . number_format($voucher->requirement_price, 0) . " ₫.",
                    'image' => 'https://cdn1.fahasa.com/skin/frontend/ma_vanese/fahasa/images/ico_coupongreen.svg?q=11027',
                    'expiry' => optional($voucher->expired_at)->format('d/m/Y'),
                    'is_used' => $group->every(fn($item) => $item->is_used),
                    'quantity' => $group->count(),
                ];
            })
            ->filter()
            ->values();

        $tabs = [
            'percent' => 'Phần trăm',
            'amount' => 'Cố định',
            'all' => 'Tất cả',
        ];

        return view('usermodule::profile.voucher', compact('vouchers', 'tabs'));
    }
}
