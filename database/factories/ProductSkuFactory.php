<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSku>
 */
class ProductSkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'sku' => strtoupper($this->faker->bothify('SKU-####')),
            'images' => json_encode(['img1.jpg', 'img2.jpg']),
            'quantity' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->numberBetween(10000, 200000),
            'sale_price' => $this->faker->numberBetween(5000, 100000),
            'expired_at' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'ISBN' => $this->faker->isbn13(),
        ];
    }
}
