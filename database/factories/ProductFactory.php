<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Publisher;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->realTextBetween(30, 80);
        return [
            'name' => $name,
            'description' => $this->faker->paragraph,
            'short_description' => $this->faker->sentence,
            'product_status' => $this->faker->randomElement(['active', 'inactive']),
            'thumbnail' => 'default-thumbnail.jpg',
            'publisher_id' => Publisher::factory(),
            'published_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'pages' => $this->faker->numberBetween(50, 1000),
            'weight' => $this->faker->randomFloat(2, 0.1, 5),
            'height' => $this->faker->randomFloat(2, 10, 30),
            'width' => $this->faker->randomFloat(2, 10, 30),
            'order_by' => $this->faker->numberBetween(1, 100),
            'author' => $this->faker->name,
            'slug' => Str::slug($name),
            'product_released_year' => $this->faker->year('-10 years'),
            'book_cover' => $this->faker->randomElement(['bìa cứng', 'bìa mềm']),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}