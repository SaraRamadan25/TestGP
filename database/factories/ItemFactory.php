<?php

namespace Database\Factories;

use App\Enums\ItemType;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => $this->faker->randomElement(ItemType::cases())->value,
            'image' => $this->faker->imageUrl(),
            'popular' => $this->faker->boolean(),
            'price' => $this->faker->numberBetween(100, 1000),
            'review' => $this->faker->sentence(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'favorite' => $this->faker->boolean(),
            'description' => $this->faker->text(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
