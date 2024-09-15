<?php

namespace Database\Factories;

use App\Models\Beneficiary;
use App\Models\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Beneficiary>
 */
class BeneficiaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_name' => Journal::BANK_NAME,
            'bank_code' => Journal::BANK_CODE,
            'bank_account_number' => fake()->iban('NG'),
            'bank_account_name' => fake()->firstName,
            'type' => fake()->randomElement(['nip', '1vault']),
        ];
    }
}
