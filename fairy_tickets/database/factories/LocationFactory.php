<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'capacity' => fake()->numberBetween(100, 5540),
            'province' => fake()->address(),
            'city' => fake()->city('es_CO'),
            'street' => fake()->streetName('es_CO'),
            'number' => fake()->buildingNumber(),
            'cp' => fake()->postcode()
        ];
    }
}
