<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Category::factory(10)->create();
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
        Notification::factory(40)->create();
        $this->call([
            ProviderSeeder::class,
        ]);
    }
}
