<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\KnowYourCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KnowYourCustomer>
 */
class KnowYourCustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bvn' => base64_encode('22219452436'),
            'doc_type' => 'drivers_license',
            'doc_image' => fake()->imageUrl,
            'selfie' => fake()->imageUrl,
            'utility_bill' => fake()->imageUrl,
            'approved_by_admin' => Customer::query()->where('role', '=', 'admin')->first()->id,
            'status' => KnowYourCustomer::ACTIVE,
            'date_attempted_account_generation' => null,
        ];
    }
}
