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
                    'name' => 'Raynor',
                    'amount' => 'Smitham',
                    'unit' => 'Leuschke',
                    'quantity' => 3,
                ],
            ],
            'note' => 'welcome',
            'amount_received' => fake()->numberBetween(1000, 100000),
            'payment_method' => 'cash',
            'discount' => fake()->numberBetween(100, 300),
            'tax' => fake()->numberBetween(10, 100),
            'shipping_fee' => fake()->numberBetween(300, 500),
            'invoice_date' => '2024-01-20',
            'due_date' => '2024-02-06',
            'invoice_number' => fake()->numberBetween(0, 1000000),
            'item_amount_total' => fake()->numberBetween(0, 1000000),
        ];
    }
}
