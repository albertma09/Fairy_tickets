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
            'cine',
            'comedia',
            'conferencia',
            'danza',
            'hogar',
            'moda',
            'musica',
            'opera',
            'salud',
            'teatro',
            
        ];

        return [
            'name' => $this->faker->unique()->randomElement($categoryNames),

        ];
    }
}
