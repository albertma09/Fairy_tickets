<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isNominal = $this->faker->boolean(20);  // probabilidad de que las entradas sean nominales o no
        $ticketAttributes = [];

        if ($isNominal) {
            $ticketAttributes['name'] = $this->faker->name;
            $ticketAttributes['dni'] = $this->faker->regexify('[0-9]{8}[A-Z]');
            $ticketAttributes['phone_number'] = $this->faker->phoneNumber;
        }

        return $ticketAttributes;
    }
}
