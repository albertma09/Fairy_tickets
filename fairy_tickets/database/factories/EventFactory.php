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
            'category_id'=>fake()->numberBetween(1,5),
            'location_id'=>fake()->numberBetween(1,3),
            'name'=>fake()->sentence,
            'description'=>fake()->paragraph,
            'price'=>fake()->randomFloat(2,1,1000),
            'date'=>fake()->date($format = 'Y-m-d',$max = 'now'),
            'hour'=>fake()->time($format = 'H:i:s', $min = 'now'),
        ];
    }
}
