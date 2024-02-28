<?php

namespace Database\Factories;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Opinion>
 */
class OpinionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'star_rating'=> fake()->numberBetween(1, 5),
            'face_rating'=> fake()->numberBetween(1, 3),
            'name' => fake()->name(),
            'title' => fake()->sentence(),
            'comment' => fake()->paragraph(),
        ];
    }


    
}
