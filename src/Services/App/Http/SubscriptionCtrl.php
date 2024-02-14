<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Service;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        return jsonResponse(Response::HTTP_OK, $subscriptions);
    }

    public function view($subscription, Request $request)
    {

    }

    /**
     * @throws Exception
     */
    //delete method will handle cancel subscription

    //view will handle data about viewing a subscription
}
