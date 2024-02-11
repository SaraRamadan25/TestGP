<?php

namespace Database\Factories;

use App\Models\Area;
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
        $area = Area::factory()->create();

        return [
            'modelno' => fake()->numberBetween(1, 100),
            'batteryLevel' => fake()->numberBetween(0, 100),
            'start_rent_time' => fake()->dateTimeBetween('now', '+1 week'),
            'end_rent_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'active'=> rand(0,1),
            'user_id' => $user->id,
            'area_id'=> $area->id,
        ];
    }
}
