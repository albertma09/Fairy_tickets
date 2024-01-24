<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryNames = [
            'Cine',
            'Comedia',
            'Conferencia',
            'Danza',
            'Exposición',
            'Moda',
            'Musica',
            'Opera',
            'Salud',
            'Teatro',
            'Feria',
            
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categoryNames),

        ];
    }
}
