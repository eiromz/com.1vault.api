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
                    'inventory_id' => fake()->uuid,
                    'name' => 'HackettLao',
                    'amount' => 100,
                    'unit' => 5,
                    'quantity' => 3,
                ],
                [
                    'inventory_id' => fake()->uuid,
                    'name' => 'Hackett',
                    'amount' => 100,
                    'unit' => 3,
                    'quantity' => 3,
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
