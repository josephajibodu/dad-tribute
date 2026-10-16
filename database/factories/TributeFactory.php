<?php

namespace Database\Factories;

use App\Models\Tribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tribute>
 */
class TributeFactory extends Factory
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
            'relation' => fake()->randomElement(['Friend', 'Colleague', 'Neighbour', null]),
            'message' => fake()->paragraph(),
            'approved_at' => null,
            'ip_hash' => hash('sha256', fake()->ipv4()),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approved_at' => now(),
        ]);
    }

    public function candleOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'message' => null,
        ]);
    }
}
