<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PayBillCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        //debit user
        //send request to the service

        $data = Cart::query()->create($request->only(['service_id', 'request_id', 'price', 'customer_id', 'account_id']));

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
