<?php

namespace Database\Factories;

use App\Models\Promocode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PromoCode>
 */
class PromocodeFactory extends Factory
{
    protected $model = Promocode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'STATIC2024',
            'expires_at' => Carbon::now()->addDays(30),
            'percentage' => 10,
        ];
    }
}
