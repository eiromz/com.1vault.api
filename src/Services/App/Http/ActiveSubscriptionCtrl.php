<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Service;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Services\App\Requests\ServiceRequest;
use Symfony\Component\HttpFoundation\Response;

class ActiveSubscriptionCtrl extends DomainBaseCtrl
{
    public function index(Request $request)
    {
        $request->validate([
            'category' => ['required', 'in:business_registration,social_media,legal,pos,store_front'],
        ]);

        $service = Service::query()
            ->where('category', '=', $request->category)
            ->get();

        return jsonResponse(Response::HTTP_OK, $service);
    }

    /**
     * @throws Exception
     */
    //delete method will handle cancel subscription

    //view will handle data about viewing a subscription
}
