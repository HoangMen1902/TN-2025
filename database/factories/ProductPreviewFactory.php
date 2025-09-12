<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductPreviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'file_path' => 'uploads/previews/' . $this->faker->uuid . '.pdf',
            'format' => 'pdf',
            'file_name' => $this->faker->word . '.pdf',
            'file_size' => $this->faker->numberBetween(100, 5000),
            'is_active' => $this->faker->boolean,
            'product_id' => Product::factory(),
        ];
    }
}