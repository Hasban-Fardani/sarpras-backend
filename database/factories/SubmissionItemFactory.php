<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubmissionItem>
 */
class SubmissionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10),
            'status' => fake()->randomElement(['diajukan', 'disetujui', 'ditolak', 'selesai']),
            'total_items' => fake()->numberBetween(1, 100),
        ];
    }
}
