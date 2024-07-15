<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['delivering', 'processing', 'cancelled']),
            'total' => $this->faker->numberBetween(100, 1000),
            'order_number' => $this->faker->unique()->randomNumber(3),
            'quantity' => $this->faker->numberBetween(1, 10),
            'date' => $this->faker->date(),
            'details' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
