<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\colour_schemes>
 */
class ColourSchemesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'colour_1' => substr($this->faker->hexColor, 1),
            'colour_2' => substr($this->faker->hexColor, 1),
            'colour_3' => substr($this->faker->hexColor, 1),
            'colour_4' => substr($this->faker->hexColor, 1),
            'colour_5' => substr($this->faker->hexColor, 1),
        ];
    }
}
