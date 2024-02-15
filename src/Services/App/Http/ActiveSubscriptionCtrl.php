<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Subscription;
use Src\Services\App\Http\Resource\SubscriptionResource;
use Symfony\Component\HttpFoundation\Response;

class ActiveSubscriptionCtrl extends DomainBaseCtrl
{
    public function __invoke()
    {
        $subscriptions = Subscription::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->with('service')
            ->get();

        dd($subscriptions);

        $activeSubscription = $subscriptions->where('is_active','=',true)->all();

        dd($activeSubscription);

        return jsonResponse(Response::HTTP_OK, SubscriptionResource::collection($activeSubscription));
    }

}
