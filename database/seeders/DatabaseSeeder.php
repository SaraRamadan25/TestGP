<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Promocode;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Item::factory(5)->create();
    }
}
