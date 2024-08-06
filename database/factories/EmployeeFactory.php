<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement(['Wakasek', 'kaprog', 'petugas TU']),
            'nip' => fake()->numberBetween(1000000000, 9999999999),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
        ];
    }
}
