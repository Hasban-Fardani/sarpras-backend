<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'code' => fake()->unique()->word(),
            'merk' => fake()->word(),
            'unit' => 'pcs',
            'price' => fake()->numberBetween(10000, 1000000),
            'stock' => fake()->numberBetween(1, 100),
            'min_stock' => fake()->numberBetween(1, 100),
            'category_id' => fake()->numberBetween(1, 10),
        ];
    }
}
