<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trainer>
 */
class TrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'area_id' => Area::factory(),
            'description' => $this->faker->sentence,
            'availability_times' => $this->faker->sentence,
            'role_id' => Role::query()->whereBetween('id', [1, 3])->inRandomOrder()->first()->id,

        ];
    }
}
