<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prize;
use Carbon\Carbon;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $prizes = [
            [
                'id' => 1,
                'name' => 'Tài Lộc 202',
                'quantity' => 10,
                'probability' => 0.01,
                
            ],
            [
                'id' => 2,
                'name' => 'MX 224',
                'quantity' => 8,
                'probability' => 0.01,
                
            ],
            [
                'id' => 3,
                'name' => 'FreeShip',
                'quantity' => 9,
                'probability' => 0.01,
                
            ],
            [
                'id' => 4,
                'name' => 'Giảm 300K',
                'quantity' => 9,
                'probability' => 0.01,
                
            ],
            [
                'id' => 5,
                'name' => 'Tặng BM',
                'quantity' => 9,
                'probability' => 0.01,
                
            ],
            [
                'id' => 6,
                'name' => 'Sách -15%',
                'quantity' => 10,
                'probability' => 0.01,
                
            ],
            [
                'id' => 7,
                'name' => 'New -100K',
                'quantity' => 9,
                'probability' => 0.01,
                
            ],
            [
                'id' => 8,
                'name' => 'Chúc bạn may mắn lần sau',
                'quantity' => null,
                'probability' => 0.02,
                
            ],
        ];

       foreach ($prizes as $prize) {
            Prize::updateOrCreate(
                ['id' => $prize['id']],
                [
                    'name' => $prize['name'],
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
