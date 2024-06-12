<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::factory()->guard()->create();
        Role::factory()->parent()->create();
        Role::factory()->trainer()->create();
    }
}
