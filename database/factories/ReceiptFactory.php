<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Receipt>
 */
class ReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'items' => [
                [
                    'name' => fake()->uuid,
                ],
                [
                    'name' => fake()->uuid,
                ],
            ],
            'description' => 'welcome',
            'amount_received' => fake()->numberBetween(1000, 100000),
            'payment_method' => 'cash',
            'tax' => fake()->numberBetween(10, 100),
            'discount' => fake()->numberBetween(100, 300),
            'total' => fake()->numberBetween(0, 1000000),
            'transaction_date' => '2024-01-20',
        ];
    }
}
