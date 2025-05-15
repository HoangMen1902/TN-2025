<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OptionValue>
 */
class OptionValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'option_id' => \App\Models\Option::factory(),
            'value_name' => $this->faker->word,
            'option_value_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
