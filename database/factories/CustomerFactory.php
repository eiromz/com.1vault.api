<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Src\Customer\App\Enum\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->email,
            'email_verified_at' => now(),
            'phone_number' => fake()->phoneNumber,
            'password' => null,
            'role' => Role::BUSINESS_OWNER->value,
            'accept_terms_conditions' => true,
            'is_owner' => true,
            'is_member' => false,
            'status' => true,
            'ACCOUNTID' => generateAccountId(),
            'transaction_pin' => 123456,
            'referral_code' => generateReferralCode(),
            'otp' => generateOtpCode(),
            'otp_expires_at' => null,
        ];
    }
}
