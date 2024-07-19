<?php
namespace Database\Seeders;

use App\Models\Item;
use App\Models\ShippingAddress;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\City;
use App\Models\District;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Country::factory()
            ->count(5)
            ->create()
            ->each(function ($country) {
                City::factory()
                    ->count(5)
                    ->for($country)
                    ->create()
                    ->each(function ($city) {
                        District::factory()
                            ->count(5)
                            ->for($city)
                            ->create();
                    });
            });

        Item::factory(5)->create();
        ShippingAddress::factory(5)->create();
    }
}
