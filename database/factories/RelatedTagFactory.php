<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RelatedTagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tag_name' => $this->faker->word,
            'related_tag_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}