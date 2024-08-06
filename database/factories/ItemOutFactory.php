<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemOut>
 */
class ItemOutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'operator_id' => fake()->numberBetween(1, 3),
            'division_id' => fake()->numberBetween(1, 3),
            'total_items' => fake()->numberBetween(1, 100),
            'note' => fake()->sentence(),
        ];
    }
}
