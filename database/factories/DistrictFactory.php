<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class DistrictFactory extends Factory
{
    protected $model = District::class;

    public function definition()
    {
        return [
            'name' => $this->faker->streetName,
            'city_id' => City::inRandomOrder()->first()->id ?? City::factory(),
        ];
    }
}
