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

        Role::insert([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'parent'],
            ['id' => 3, 'name' => 'guard'],
            ['id' => 4, 'name' => 'trainer'],
        ]);

        User::factory(4)->create();
        QrCode::factory(4)->create();
        Jacket::factory(4)->create();
        Area::factory(4)->create();
        Instruction::factory(4)->create();
        Location::factory(4)->create();
        Session::factory(4)->create();

    }
}
