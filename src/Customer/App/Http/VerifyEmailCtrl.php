<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Src\Customer\App\Http\Data\VerifyEmailData;
use Src\Customer\Domain\Mail\VerificationEmail;
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

        $name = str_replace('@','',$request->customer->email);

        $customer = Customer::where('email',$request->customer->email)->first();

        $token = $customer->createToken($name,Customer::OWNER_ABILITIES)->plainTextToken;

        $customer = $request->customer;
        $customer->token = $token;

        return jsonResponse(Response::HTTP_OK,$customer);
    }
}
