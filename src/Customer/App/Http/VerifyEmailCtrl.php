<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Src\Customer\App\Http\Data\VerifyEmailData;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailCtrl extends DomainBaseCtrl
{
    /**
     * @throws \Exception
     */
    public function store(VerifyEmailData $request): JsonResponse
    {
        $request->toArray();
        $request->execute();

        $customer = Customer::where('email', $request->customer->email)->first();

        $token = $customer->createToken(
            createNameForToken($request->customer->email), Customer::OWNER_ABILITIES
        )->plainTextToken;

        $customer = $request->customer;
        $customer->token = $token;

        return jsonResponse(Response::HTTP_OK, $customer);
    }
}
