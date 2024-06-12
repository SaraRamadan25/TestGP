<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['parent', 'guard', 'trainer']),
        ];
    }

    public function parent()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 1,
                'name' => 'parent',
            ];
        });
    }

    public function guard()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 2,
                'name' => 'guard',
            ];
        });
    }

    public function trainer()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 3,
                'name' => 'trainer',
            ];
        });
    }
}
