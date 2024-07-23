<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RequestItemDetail>
 */
class RequestItemDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 100);
        return [
            'request_item_id' => fake()->numberBetween(1, 10),
            'item_id' => fake()->numberBetween(1, 10),
            'qty' => $qty,
            'qty_acc' => fake()->numberBetween(1, $qty),
        ];
    }
}
