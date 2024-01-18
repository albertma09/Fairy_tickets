<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => fake()->numberBetween(1, 10),
            'location_id' => fake()->numberBetween(1, 3),
            'user_id' => fake()->numberBetween(1, 2),
            'name' => fake()->catchPhrase(),
            'description' => fake()->paragraph,
            'price' => fake()->randomFloat(2, 1, 1000),
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'hour' => fake()->time($format = 'H:i:s', $min = 'now'),
            'hidden' => fake()->boolean(10),
            'nominal_tickets' => fake()->boolean(20),
        ];
    }
}
