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
                'benefits' => 'Tặng Voucher giảm giá 50.000VND cho đơn hàng tiếp theo',
                'voucher_id' => 1,
            ],
            [
                'name' => 'Bạc',
                'required_points' => 2000,
                'benefits' => 'Tặng Voucher giảm giá 150.000VND cho đơn hàng tiếp theo',
                'voucher_id' => 2,
            ],
            [
                'name' => 'Vàng',
                'required_points' => 3000,
                'benefits' => 'Tặng Voucher giảm giá 200.000VND cho đơn hàng tiếp theo',
                'voucher_id' => 3,  
            ],
            [
                'name' => 'Kim cương',
                'required_points' => 5000,
                'benefits' => 'Tặng Voucher giảm giá 250.000VND cho đơn hàng tiếp theo',
                'voucher_id' => 4,
            ],
            
        ]);
    }
}
