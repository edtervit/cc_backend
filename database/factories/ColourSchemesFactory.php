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
            'colour_1' => $this->faker->hexColor,
            'colour_2' => $this->faker->hexColor,
            'colour_3' => $this->faker->hexColor,
            'colour_4' => $this->faker->hexColor,
            'colour_5' => $this->faker->hexColor,
        ];
    }
}
