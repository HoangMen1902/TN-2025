<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prize;
class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $prizes = [
        ['name' => 'Giảm 10%', 'probability' => 0.15],
        ['name' => '-20K đơn ≥150K', 'probability' => 0.1],
        ['name' => 'Free Ship', 'probability' => 0.1],
        ['name' => '-50K đơn ≥300K', 'probability' => 0.1],
        ['name' => 'Mua 2 tặng bookmark', 'probability' => 0.15],
        ['name' => 'Giảm 15% sách mới', 'probability' => 0.1],
        ['name' => '-100K khách mới', 'probability' => 0.05],
        ['name' => '+1 lượt quay', 'probability' => 0.25],
    ];

    foreach ($prizes as $prize) {
        Prize::create([
            'name' => $prize['name'],
            'probability' => $prize['probability'],
            'quantity' => rand(1, 10),
        ]);
    }
}
}
