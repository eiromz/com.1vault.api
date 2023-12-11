<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Src\Customer\App\Http\Data\RegisterCustomerData;
use Src\Customer\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;

class RegisterCustomerCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(RegisterCustomerData $request): JsonResponse
    {
        $request->toArray();
        $request->newCustomerInstance()->save();

        Mail::to($request->customer->email)->queue(new VerificationEmail($request->customer->otp));

        return jsonResponse(Response::HTTP_OK, $request->customer);
    }
}
