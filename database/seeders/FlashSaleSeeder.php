<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashsale;
use App\Models\FlashsaleDiscount;
use App\Models\ProductSku;
use Illuminate\Support\Carbon;

class FlashsaleSeeder extends Seeder
{
    public function run()
    {
        $flashsale1 = Flashsale::create([
            'name' => 'Flash Sale - Giảm 20%',
            'started_at' => Carbon::now()->subHour(),
            'expired_at' => Carbon::now()->addHours(24),
        ]);

        $discount1 = FlashsaleDiscount::create([
            'flashsale_id' => $flashsale1->id,
            'discount_type' => 'percent',
            'discount_amount' => 20,
        ]);

        $skus1 = ProductSku::inRandomOrder()->limit(10)->get();
        $flashsale1->skus()->attach($skus1->pluck('id')->toArray());
        $flashsale2 = Flashsale::create([
            'name' => 'Flash Sale - Giảm 30.000đ',
            'started_at' => Carbon::now()->subHour(),
            'expired_at' => Carbon::now()->addHours(10), 
        ]);

        $discount2 = FlashsaleDiscount::create([
            'flashsale_id' => $flashsale2->id,
            'discount_type' => 'specific',
            'discount_amount' => 30000,
        ]);

        $skus2 = ProductSku::inRandomOrder()->limit(20)->get();
        $flashsale2->skus()->attach($skus2->pluck('id')->toArray());
    }
}
