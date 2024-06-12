<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Area;
use App\Models\Instruction;
use App\Models\Jacket;
use App\Models\Location;
use App\Models\QrCode;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::factory()->state(['id' => 1, 'name' => 'parent'])->create();

        Role::factory()->state(['id' => 2, 'name' => 'guard'])->create();

        Role::factory()->state(['id' => 3, 'name' => 'trainer'])->create();

        User::factory(4)->create();
        QrCode::factory(4)->create();
        Jacket::factory(4)->create();
        Area::factory(4)->create();
        Instruction::factory(4)->create();
        Location::factory(4)->create();
        Session::factory(4)->create();

    }
}
