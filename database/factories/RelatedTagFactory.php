<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RelatedTag>
 */
class RelatedTagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag_name' => $this->faker->word,
            'related_tags_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
