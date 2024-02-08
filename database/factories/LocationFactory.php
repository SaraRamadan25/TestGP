<?php

namespace Database\Factories;

use App\Models\Jacket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
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
            'longitude' => number_format(rand(0, 180000000) / 1000000, 6),
            'latitude' => number_format(rand(0, 18000000) / 1000000, 6),
            'jacket_id'=> $jacket->id,
        ];
    }
}
