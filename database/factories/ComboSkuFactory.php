<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductSku;
use App\Models\ProductCombo;

class ComboSkuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku_id' => ProductSku::factory(),
            'combo_id' => ProductCombo::factory(),
        ];
    }
}