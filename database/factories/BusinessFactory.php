<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullname' => fake()->lastName,
            'phone_number' => fake()->phoneNumber,
            'email' => fake()->email,
            'address' => fake()->address,
            'image' => fake()->imageUrl,
            'zip_code' => fake()->postcode,
        ];
    }
}
