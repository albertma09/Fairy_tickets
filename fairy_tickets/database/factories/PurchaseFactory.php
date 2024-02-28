<?php

namespace Database\Factories;

use App\Models\Opinion;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Session;
use App\Models\Purchase;
use App\Models\Ticket;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $session = Session::inRandomOrder()->first();
        return [
            'session_id' => $session->id,
            'name' => $this->faker->name,
            'dni' => $this->faker->regexify('[0-9]{8}[A-Z]'),
            'phone_number' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Purchase $purchase) {
            $ticketType = $purchase->session->ticketTypes()->inRandomOrder()->first();

            

            $tickets = Ticket::factory()->count(rand(1, 4))->make(['ticket_type_id' => $ticketType->id])->toArray();
            foreach ($tickets as $ticket) {
                $purchase->tickets()->create($ticket);
            }


            $opinions = Opinion::factory()->count(1)->make()->toArray();

            $purchase->opinion()->create($opinions[0]);
        });
    }
}
