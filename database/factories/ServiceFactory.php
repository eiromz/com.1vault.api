<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $billing_cycle = ['one-time','monthly','quarterly','yearly'];
        return [
            'title'         => fake()->lastName,
            'type'          => fake()->randomElement(['airtime','data','electricity','legal']),
            'provider'      => 'wema bank',
            'description'   => fake()->lastName,
            'amount'        => fake()->numberBetween(1000,100000),
            'commission'    => fake()->numberBetween(1000,100000),
            'is_recurring'  => fake()->boolean,
            'billing_cycle' => 'monthly',
            'is_request'    => fake()->boolean(50),
            'discount'      => fake()->numberBetween(1000,100000),
            'status'        => fake()->boolean,
            'category'      => fake()->randomElement(['social_media','business_registration','legal','pos','store_front']),
            'benefit'       => [
                'content_creation','building house', 'running helter skelter'
            ],
            'quantity'      => 8,
        ];
    }
}
