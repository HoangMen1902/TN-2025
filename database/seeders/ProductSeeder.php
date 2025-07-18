<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flashsale;
use App\Models\FlashsaleDiscount;
use App\Models\ProductSku;
use Illuminate\Support\Carbon;

class ProductSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Publisher::factory(5)->create();
        \App\Models\Product::factory(20)->create();
        \App\Models\ProductSku::factory(40)->create();
        \App\Models\ProductCombo::factory(10)->create();
        \App\Models\ComboSku::factory(20)->create();
        \App\Models\Option::factory(5)->create();
        \App\Models\OptionValue::factory(15)->create();
        \App\Models\SkuValue::factory(20)->create();
        \App\Models\ProductPreview::factory(20)->create();
        \App\Models\RelatedTag::factory(10)->create();
        \App\Models\ProductTag::factory(20)->create();
        \App\Models\ProductCategory::factory(30)->create();
        $this->call([
            CategorySeeder::class,
        ]);
    }
}
