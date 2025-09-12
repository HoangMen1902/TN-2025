<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Option;

class OptionValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'option_id' => Option::factory(),
            'value_name' => $this->faker->word,
            'option_value_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}