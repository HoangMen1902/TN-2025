<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Publisher;
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
        $name = $this->faker->words(3, true);
        $releasedYear = $this->faker->year('-10 years'); 

        return [
            'name' => $name,
            'description' => $this->faker->paragraph,
            'short_description' => $this->faker->sentence,
            'product_status' => $this->faker->randomElement(['active', 'inactive']),
            'thumbnail' => 'default-thumbnail.jpg',
            'publisher_id' => Publisher::factory(),
            'published_at' => $this->faker->dateTimeBetween('-1 years', 'now'),

            'pages' => $this->faker->numberBetween(50, 1000),
            'weight' => $this->faker->randomFloat(2, 0.1, 5), 
            'height' => $this->faker->randomFloat(2, 10, 30),  
            'width' => $this->faker->randomFloat(2, 10, 30),  
            'order_by' => $this->faker->numberBetween(1, 100),
            'author' => $this->faker->name,
            'slug' => Str::slug($name),
            'product_released_year' => $releasedYear,
            'book_cover' => $this->faker->randomElement(['bìa cứng', 'bìa mềm']),
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,  // hoặc $this->faker->optional()->dateTime()
        ];
    }
}
