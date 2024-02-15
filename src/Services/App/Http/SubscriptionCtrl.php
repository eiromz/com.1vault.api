<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Service;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Services\App\Http\Resource\SubscriptionResource;
use Src\Services\App\Requests\ServiceRequest;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionCtrl extends DomainBaseCtrl
{
    public function index()
    {
        $subscriptions = Subscription::query()
            ->where('customer_id', '=', auth()->user()->id)
            ->with('service')
            ->get();

        return jsonResponse(Response::HTTP_OK, SubscriptionResource::collection($subscriptions));
    }
    public function view($subscription, Request $request)
    {
        $request
            ->merge(['subscription' => $subscription])
            ->validate(['subscription' => ['required','exists:App\Models\Subscription,id']]);

        $subscription = Subscription::query()
            ->where('id','=',$request->subscription)
            ->where('customer_id', '=', auth()->user()->id)
            ->with('service')
            ->firstOrFail();

        return jsonResponse(Response::HTTP_OK, new SubscriptionResource($subscription));
    }
}
