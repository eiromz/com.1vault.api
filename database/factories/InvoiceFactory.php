<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
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
            'note' => 'welcome',
            'amount_received' => fake()->numberBetween(1000, 100000),
            'payment_method' => 'cash',
            'discount' => fake()->numberBetween(100, 300),
            'tax' => fake()->numberBetween(10, 100),
            'shipping_fee' => fake()->numberBetween(300, 500),
            'invoice_date' => '2024-01-01',
            'due_date' => '2024-02-10',
            'total' => fake()->numberBetween(0, 1000000),
        ];
    }
}
