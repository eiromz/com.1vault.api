<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Src\Services\App\Enum\BillingCycle;

class BusinessRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $packages = $this->packages();

        foreach ($packages as $package) {
            Service::query()->create($package);
        }
    }

    public function packages()
    {
        return [
            [
                'title' => 'Register a business name in Nigeria',
                'type' => 'business_name',
                'provider' => '1vault',
                'description' => 'Let our experts take the stress off you when it comes to registering your business name',
                'amount' => 20000,
                'commission' => 0,
                'is_recurring' => 0,
                'billing_cycle' => BillingCycle::ONETIME->value,
                'is_request' => 1,
                'discount' => 0,
                'has_discount' => 0,
                'status' => 1,
                'category' => 'business_registration',
                'benefit' => [
                    'content_creation',
                ],
                'quantity' => 0,
            ],
            [
                'title' => 'Register a Limited Liability Company in Nigeria',
                'type' => 'business_llc',
                'provider' => '1vault',
                'description' => 'Let our experts take the stress off you when it comes to registering your LLC name',
                'amount' => 70000,
                'commission' => 0,
                'is_recurring' => 0,
                'billing_cycle' => BillingCycle::ONETIME->value,
                'is_request' => 1,
                'discount' => 0,
                'has_discount' => 0,
                'status' => 1,
                'category' => 'business_registration',
                'benefit' => [
                    'content_creation',
                ],
                'quantity' => 0,
            ],
        ];
    }
}
