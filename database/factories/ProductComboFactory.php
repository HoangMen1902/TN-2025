<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCombo>
 */
class ProductComboFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'combo_name' => $this->faker->sentence,
            'images' => $this->faker->sentence,
            'slug' => $this->faker->sentence,
            'original_price' => $this->faker->numberBetween(100000, 300000),
            'sale_price' => $this->faker->numberBetween(50000, 250000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'expired_at' => $this->faker->dateTimeBetween('+1 week', '+3 months'),
        ];
    }
}
