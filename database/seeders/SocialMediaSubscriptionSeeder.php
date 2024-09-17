<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Src\Services\App\Enum\BillingCycle;

class SocialMediaSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
                'title' => 'Basic',
                'type' => 'service.social_media',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 50000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::MONTHLY->value,
                'is_request' => 0,
                'discount' => 0,
                'has_discount' => false,
                'status' => 1,
                'category' => 'social_media',
                'benefit' => [
                    'Content Generation',
                    'Graphic Design',
                    'Sponsored ads',
                    'Posting across Instagram,Facebook and Twitter',
                ],
                'quantity' => 8,
            ],
            [
                'title' => 'Business',
                'type' => 'service.social_media',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 73000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::MONTHLY->value,
                'is_request' => 0,
                'discount' => 0,
                'has_discount' => false,
                'status' => 1,
                'category' => 'social_media',
                'benefit' => [
                    'Content Generation',
                    'Graphic Design',
                    'Sponsored ads',
                    'Posting across Instagram,Facebook and Twitter',
                ],
                'quantity' => 12,
            ],
            [
                'title' => 'Enterprise',
                'type' => 'service.social_media',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 96000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::MONTHLY->value,
                'is_request' => 0,
                'discount' => 0,
                'has_discount' => false,
                'status' => 1,
                'category' => 'social_media',
                'benefit' => [
                    'Content Generation',
                    'Graphic Design',
                    'Sponsored ads',
                    'Posting across Instagram,Facebook and Twitter',
                ],
                'quantity' => 16,
            ],
        ];
    }

    public function yearly()
    {
        return [
            [
                'title' => 'Basic',
                'type' => 'service.social_media',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 540000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::YEARLY->value,
                'is_request' => 0,
                'discount' => 10,
                'has_discount' => true,
                'status' => 1,
                'category' => 'social_media',
                'benefit' => [
                    'Content Generation',
                    'Graphic Design',
                    'Sponsored ads',
                    'Posting across Instagram,Facebook and Twitter',
                ],
                'quantity' => 8,
            ],
            [
                'title' => 'Business',
                'type' => 'service.social_media',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 788400,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::YEARLY->value,
                'is_request' => 0,
                'discount' => 10,
                'has_discount' => true,
                'status' => 1,
                'category' => 'social_media',
                'benefit' => [
                    'Content Generation',
                    'Graphic Design',
                    'Sponsored ads',
                    'Posting across Instagram,Facebook and Twitter',
                ],
                'quantity' => 12,
            ],
            [
                'title' => 'Enterprise',
                'type' => 'service.social_media',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 1036800,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::YEARLY->value,
                'is_request' => 0,
                'discount' => 10,
                'has_discount' => true,
                'status' => 1,
                'category' => 'social_media',
                'benefit' => [
                    'Content Generation',
                    'Graphic Design',
                    'Sponsored ads',
                    'Posting across Instagram,Facebook and Twitter',
                ],
                'quantity' => 16,
            ],
        ];
    }
}
