<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductSku;
use App\Models\Option;
use App\Models\OptionValue;

class SkuValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku_id' => ProductSku::factory(),
            'option_id' => Option::factory(),
            'value_id' => OptionValue::factory(),
        ];
    }
}