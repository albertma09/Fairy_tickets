<?php

namespace Database\Factories;

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
            $tickets = Ticket::factory()->count(rand(1, 4))->make()->toArray();
            foreach ($tickets as $ticket) {
                $purchase->tickets()->create($ticket);
            }
        });
    }
}
