<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Src\Services\App\Enum\BillingCycle;

class StoreFrontSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $quarterly = $this->quarterly();
        $yearly  = $this->yearly();

        foreach($quarterly as $quarter) {
            Service::query()->create($quarter);
        }

        foreach($yearly as $year) {
            Service::query()->create($year);
        }
    }

    public function quarterly()
    {
        return [
            [
                'title' => 'Basic',
                'type' => 'service.store_front',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 4500,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::QUARTERLY->value,
                'is_request' => 0,
                'discount' => 0,
                'status' => 1,
                'category' => 'store_front',
                'benefit' => [
                    'Store url created for your shop',
                    'Receive payments directly to your 1vault wallet',
                    '50 product listings',
                    'No hidden charges or commissions',
                ],
                'quantity' => 0,
            ],
        ];
    }

    public function yearly()
    {
        return [
            [
                'title' => 'Basic',
                'type' => 'service.store_front',
                'provider' => '1vault',
                'description' => 'N/A',
                'amount' => 15000,
                'commission' => 0,
                'is_recurring' => 1,
                'billing_cycle' => BillingCycle::YEARLY->value,
                'is_request' => 0,
                'discount' => 17,
                'has_discount' => true,
                'status' => 1,
                'category' => 'store_front',
                'benefit' => [
                    'Store url created for your shop',
                    'Receive payments directly to your 1vault wallet',
                    '50 product listings',
                    'No hidden charges or commissions',
                ],
                'quantity' => 0,
            ],
        ];
    }
}
