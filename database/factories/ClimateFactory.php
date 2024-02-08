<?php

namespace Database\Factories;

use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Climate>
 */
class ClimateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $area = Area::factory()->create();

        return [
            'tide' =>fake()->randomFloat(2, 0, 10),
            'sea_level' => fake()->randomFloat(2, 0, 100),
            'wind' => fake()->randomFloat(2, 0, 50),
            'temperature' => fake()->randomFloat(2, -10, 40),
            'day_name' => fake()->dayOfWeek,
            'day_date' => fake()->date(),
            'area_id'=> $area->id,
        ];
    }
}
