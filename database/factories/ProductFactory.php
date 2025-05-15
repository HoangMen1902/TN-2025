<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'short_description' => $this->faker->sentence,
            'products_status' => $this->faker->randomElement(['active', 'inactive']),
            'thumbnail' => 'default-thumbnail.jpg',
            'published_at' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'publisher_id' => \App\Models\Publisher::factory(),
        ];
    }
}
