<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->jobTitle,
        ];
    }

    /**
     * Define the state for a guard role.
     */
    public function guard(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'guard',
            ];
        });
    }

    /**
     * Define the state for a parent role.
     */
    public function parent(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'parent',
            ];
        });
    }

    /**
     * Define the state for a trainer role.
     */
    public function trainer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'trainer',
            ];
        });
    }
}
