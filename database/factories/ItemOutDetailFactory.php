<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemOutDetail>
 */
class ItemOutDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_out_id' => fake()->numberBetween(1, 10),
            'item_id' => fake()->numberBetween(1, 10),
            'qty' => fake()->numberBetween(1, 100),
        ];
    }
}
