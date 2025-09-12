<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prize;
use App\Models\Voucher;
use Carbon\Carbon;

class PrizeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $prizes = [
            [
                'name' => 'Tài Lộc 202',
                'quantity' => 10,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_TaiLoc202')->value('id'),
            ],
            [
                'name' => 'MX 224',
                'quantity' => 8,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_MX224')->value('id'),
            ],
            [
                'name' => 'FreeShip',
                'quantity' => 9,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_FreeShip')->value('id'),
            ],
            [
                'name' => 'Giảm 300K',
                'quantity' => 9,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_Giam300K')->value('id'),
            ],
            [
                'name' => 'Tặng BM',
                'quantity' => 9,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_TangBM')->value('id'),
            ],
            [
                'name' => 'Sách -15%',
                'quantity' => 10,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_Sach15')->value('id'),
            ],
            [
                'name' => 'New -100K',
                'quantity' => 9,
                'probability' => 0.01,
                'voucher_id' => Voucher::where('voucher_code', 'Prize_New100K')->value('id'),
            ],
            [
                'name' => 'Chúc bạn may mắn lần sau',
                'quantity' => null,
                'probability' => 0.02,
                'voucher_id' => null,
            ],
        ];

        foreach ($prizes as $prize) {
            Prize::updateOrCreate(
                ['name' => $prize['name']],
                [
                    'quantity' => $prize['quantity'],
                    'probability' => $prize['probability'],
                    'voucher_id' => $prize['voucher_id'] ?? null,
                    'created_at' => '2025-06-30 23:30:17',
                    'updated_at' => $now,
                ]
            );
        }
    }
}
