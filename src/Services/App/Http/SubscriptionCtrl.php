<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Services\App\Http\Resource\SubscriptionResource;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionCtrl extends DomainBaseCtrl
{
    private $subscriptions;

    public function index($status, Request $request): JsonResponse
    {
        $request->merge(['status' => $status])->validate([
            'status' => ['required', 'string', 'in:default,active'],
        ]);

        $this->subscriptions = Subscription::query()->where('customer_id', '=', auth()->user()->id)->with('service');

        $subscriptions = match ($request->status) {
            'default' => SubscriptionResource::collection($this->subscriptions->get()),
            'active' => $this->subscriptions->where('expiration_date', '>', now())->get()->pluck('service.category'),
        };

        return jsonResponse(Response::HTTP_OK, $subscriptions);
    }

    public function view($subscription, Request $request): JsonResponse
    {
        $request
            ->merge(['subscription' => $subscription])
            ->validate(['subscription' => ['required', 'exists:App\Models\Subscription,id']]);

        $subscription = Subscription::query()
            ->where('id', '=', $request->subscription)
            ->where('customer_id', '=', auth()->user()->id)
            ->with('service')
            ->firstOrFail();

        return jsonResponse(Response::HTTP_OK, new SubscriptionResource($subscription));
    }
}
