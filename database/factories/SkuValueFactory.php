<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SkuValue>
 */
class SkuValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku_id' => \App\Models\ProductSku::factory(),
            'option_id' => \App\Models\Option::factory(),
            'value_id' => \App\Models\OptionValue::factory(),
        ];
    }
}
