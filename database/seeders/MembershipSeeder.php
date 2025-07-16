<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('memberships')->insert([
            [
                'name' => 'Đồng',
                'required_points' => 1000,
                'benefits' => 'Tặng Voucher giảm giá cho đơn hàng tiếp theo',
            ],
            [
                'name' => 'Bạc',
                'required_points' => 2000,
                'benefits' => 'Tặng Voucher giảm giá cho đơn hàng tiếp theo',
            ],
            [
                'name' => 'Vàng',
                'required_points' => 3000,
                'benefits' => 'Tặng Voucher giảm giá cho đơn hàng tiếp theo',
            ],
            [
                'name' => 'Kim cương',
                'required_points' => 5000,
                'benefits' => 'Tặng Voucher giảm giá cho đơn hàng tiếp theo',
            ],
            
        ]);
    }
}
