<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Session;
use App\Models\TicketType;

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
            'date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'hour' => fake()->time($format = 'H:i:s', $min = 'now')
        ];
    }

    /**
     * Función que crea de 1 a 4 tipos de ticket relacionadas con una sesión cuando esta se acaba de crear por factory
     *
     * @return void
     */
    public function configure()
    {
        return $this->afterCreating(function (Session $session) {
            $ticketTypes = TicketType::factory()->count(rand(1, 4))->make()->toArray();
            foreach ($ticketTypes as $ticketType) {
                $session->ticketTypes()->create($ticketType);
            }
        });
    }
}