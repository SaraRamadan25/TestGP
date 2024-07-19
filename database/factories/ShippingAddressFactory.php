<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ShippingAddressFactory extends Factory
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
            'full_name' => $this->faker->name,
            'address' => $this->faker->address,
            'postal_code' => $this->faker->postcode,
            'country_id' => Country::factory(),
            'city_id' => City::factory(),
            'district_id' => District::factory(),
        ];
    }
}
