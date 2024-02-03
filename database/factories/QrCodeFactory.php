<?php

namespace Database\Factories;

use App\Models\Jacket;
use App\Models\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QrCodeFactory extends Factory
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
            'jacket_id' => $jacket->id,
            'content' => Str::random(8)
        ];
    }
}
