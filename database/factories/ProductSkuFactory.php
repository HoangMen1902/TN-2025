<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductSkuFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper($this->faker->bothify('SKU-####')),
            'images' => json_encode(["01JXKPZK962XXCZB70T6GJQ3Z4.jpg", "01JXKPZK97P91ZJ62WHJ8PRQ6Q.jpg", "01JXKPZK9896XF9Z1WJA6Y7257.jpg", "01JXKPZK99FYZ8GB6WJMMJ98RP.jpg", "01JXKPZK9ABD8CP6CB21D334A5.png", "01JXKPZK9BPY9YRCEKZYRYJMSZ.png"]),
            'quantity' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->numberBetween(10000, 200000),
            'sale_price' => $this->faker->numberBetween(5000, 100000),
            'expired_at' => $this->faker->dateTimeBetween('+1 month', '+1 year'),
            'ISBN' => $this->faker->isbn13(),
        ];
    }
}
