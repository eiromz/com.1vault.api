<?php

namespace Src\Wallets\Payments\Domain\Services;

use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionService
{
    public $subscription;
    public Carbon $now;
    public Carbon $expiration;
    public function __construct(public $cart,public $merchant,public $request)
    {
        $this->now = now();
        $this->subscription = $cart;
        $this->expiration =determineExpirationDate(
            $this->now,$this->subscription->service->billing_cycle
        );
    }
    public function execute()
    {
        if(!$this->subscription->service?->is_recurring){
            return;
        }

        return Subscription::query()->create($this->createSubscriptionData());
    }

    public function createSubscriptionData(): array
    {
        return [
            'customer_id'       => $this->merchant->id,
            'service_id'        => $this->subscription->service->id,
            'amount'            => $this->subscription->price,
            'trx_ref'           => $this->request['trx_ref'],
            'source'            => $this->request['source'],
            'auto_renewal'      => true,
            'subscription_date' => $this->now,
            'expiration_date'   => $this->expiration,
        ];
    }
}
