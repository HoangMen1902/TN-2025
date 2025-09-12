<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Color', 'Size', 'Format']),
            'option_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}