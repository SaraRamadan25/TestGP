<?php

namespace Database\Factories;

use App\Models\Jacket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VitalSign>
 */
class VitalSignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jacket = Jacket::factory()->create();

        return [
            'heart_rate' => fake()->randomNumber(3),
            'oxygen_rate'=> fake()->randomNumber(2),
            'jacket_id'=> $jacket->id
        ];
    }
}
