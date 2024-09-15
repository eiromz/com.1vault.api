<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Src\Services\App\Enum\BillingCycle;

class BusinessAnalyticsSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $monthly = $this->monthly();
        $yearly = $this->yearly();

        foreach ($monthly as $month) {
            Service::query()->create($month);
        }

        foreach ($yearly as $year) {
            Service::query()->create($year);
        }
    }

    public function monthly()
    {
        return [
            [
                'title' => 'Business Analytics',
                'type' => 'business_analytics',
                'provider' => '1vault',
                'description' => 'Make informed data-driven decisions for your business',
                'amount' => 20000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::MONTHLY->value,
                'is_request' => 0,
                'discount' => 0,
                'has_discount' => 0,
                'status' => 1,
                'category' => 'business_analytics',
                'benefit' => [
                    'Analytics on Accounting Solutions',
                    'Analytics on Financial transactions made',
                    'Analytics on Social Media presence',
                    'Analytics on Online Storefront',
                ],
                'quantity' => 0,
            ],
        ];
    }

    public function yearly()
    {
        return [
            [
                'title' => 'Business Analytics',
                'type' => 'business_analytics',
                'provider' => '1vault',
                'description' => 'Make informed data-driven decisions for your business',
                'amount' => 216000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::YEARLY->value,
                'is_request' => 0,
                'discount' => 10,
                'has_discount' => 1,
                'status' => 1,
                'category' => 'business_analytics',
                'benefit' => [
                    'Analytics on Accounting Solutions',
                    'Analytics on Financial transactions made',
                    'Analytics on Social Media presence',
                    'Analytics on Online Storefront',
                ],
                'quantity' => 0,
            ],
        ];
    }
}
