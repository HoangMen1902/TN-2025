<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\RelatedTag;

class ProductTagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'tag_id' => RelatedTag::factory(),
        ];
    }
}