<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPreview>
 */
class ProductPreviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_path' => 'uploads/previews/' . $this->faker->uuid . '.pdf',
            'format' => 'pdf',
            'file_name' => $this->faker->word . '.pdf',
            'file_size' => $this->faker->numberBetween(100, 5000),
            'is_active' => $this->faker->boolean,
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
