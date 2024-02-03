<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jacket>
 */
class JacketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'modelno' => $this->faker->numberBetween(1, 100),
            'user_id' => $user->id,
            'batteryLevel' => $this->faker->numberBetween(0, 100),
            'start_rent_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_rent_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
        ];
    }
}
