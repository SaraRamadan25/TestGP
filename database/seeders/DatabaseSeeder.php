<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Area;
use App\Models\Climate;
use App\Models\Instruction;
use App\Models\Jacket;
use App\Models\Location;
use App\Models\QrCode;
use App\Models\User;
use App\Models\VitalSign;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(4)->create();
        QrCode::factory(4)->create();
        Jacket::factory(4)->create();
        Area::factory(4)->create();
        Climate::factory(4)->create();
        Instruction::factory(4)->create();
        Location::factory(4)->create();
        VitalSign::factory(4)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
