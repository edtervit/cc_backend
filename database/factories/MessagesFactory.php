<?php

namespace Database\Factories;

use App\Models\Messages;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Messages>
 */
class MessagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'message' => $this->faker->sentence(),
            'type' => Messages::ALL_MESSAGE_TYPES[rand(0, count(Messages::ALL_MESSAGE_TYPES) - 1)],
            'is_oblique' => (bool)rand(0, 1),
        ];
    }
}
