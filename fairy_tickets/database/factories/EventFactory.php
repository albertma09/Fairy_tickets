<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\Session;

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
        ];
    }
    /**
     * Función que crea de 1 a 4 sesiones relacionadas con un evento cuando este se acaba de crear por factory
     *
     * @return void
     */
    public function configure()
    {
        return $this->afterCreating(function (Event $event) {
            $sessions = Session::factory()->count(rand(1, 4))->make()->toArray();
            foreach ($sessions as $session) {
                $event->sessions()->create($session);
            }
        });
    }
}
