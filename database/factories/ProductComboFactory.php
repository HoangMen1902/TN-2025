<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductComboFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'combo_name' => $this->faker->sentence,
            'images' => json_encode([$this->faker->imageUrl()]),
            'slug' => $this->faker->slug,
            'original_price' => $this->faker->numberBetween(100000, 300000),
            'sale_price' => $this->faker->numberBetween(50000, 250000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'expired_at' => $this->faker->dateTimeBetween('+1 week', '+3 months'),
        ];
    }
}