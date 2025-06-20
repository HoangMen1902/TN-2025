<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PublisherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'publisher_name' => $this->faker->company,
            'publisher_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}