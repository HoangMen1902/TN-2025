<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $vouchers = [
            [
                'voucher_code' => 'ThanhVienHangDong',
                'voucher_name' => 'Giảm 50,000đ',
                'requirement_price' => 200000, 
                'reduced_amount' => 50000,
                'voucher_type' => 'amount', 
                'expired_at' => $now->copy()->addDays(30),
                'voucher_status' => 'active',
                'max_discount_amount' => null,
                'quantity' => 100,
                'usage_per_user' => 1,
                'voucher_scope' => 'global', // toàn bộ sản phẩm
                'start_at' => $now,
                'is_redeemable' => false,
                'required_points' => null,
                'issued_by' => 'membership',
                'membership_id' => 1,
            ],
            [
                'voucher_code' => 'ThanhVienHangBac',
                'voucher_name' => 'Giảm 150,000đ',
                'requirement_price' => 300000,
                'reduced_amount' => 150000,
                'voucher_type' => 'amount', 
                'expired_at' => $now->copy()->addDays(30),
                'voucher_status' => 'active',
                'max_discount_amount' => null,
                'quantity' => 100,
                'usage_per_user' => 1,
                'voucher_scope' => 'global',
                'start_at' => $now,
                'is_redeemable' => false,
                'required_points' => null,
                'issued_by' => 'membership',
                'membership_id' => 2,
            ],
            [
                'voucher_code' => 'ThanhVienHangVang',
                'voucher_name' => 'Giảm 200,000đ',
                'requirement_price' => 4000000,
                'reduced_amount' => 200000,
                'voucher_type' => 'amount', 
                'expired_at' => $now->copy()->addDays(30),
                'voucher_status' => 'active',
                'max_discount_amount' => null,
                'quantity' => 100,
                'usage_per_user' => 1,
                'voucher_scope' => 'global',
                'start_at' => $now,
                'is_redeemable' => false,
                'required_points' => null,
                'issued_by' => 'membership',
                'membership_id' => 3,
            ],
            [
                'voucher_code' => 'ThanhVienHangKimCuong',
                'voucher_name' => 'Giảm 250,000đ',
                'requirement_price' => 500000,
                'reduced_amount' => 250000,
                'voucher_type' => 'amount', 
                'expired_at' => $now->copy()->addDays(30),
                'voucher_status' => 'active',
                'max_discount_amount' => null,
                'quantity' => 100,
                'usage_per_user' => 1,
                'voucher_scope' => 'global',
                'start_at' => $now,
                'is_redeemable' => false,
                'required_points' => null,
                'issued_by' => 'membership',
                'membership_id' => 4,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::create($voucher);
        }
    }
}
