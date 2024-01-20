<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->lastName,
            'amount' => fake()->numberBetween(100, 1000),
            'selling_price' => fake()->numberBetween(100, 1000),
            'quantity' => fake()->numberBetween(100, 1000),
            'unit' => fake()->numberBetween(100, 1000),
        ];
    }
}
