<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Session>
 */
class SessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => fake()->numberBetween(1, 10),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'hour' => fake()->time($format = 'H:i:s', $min = 'now')
        ];
    }
}
