<?php

namespace Src\Customer\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Support\Facades\Mail;
use Src\Customer\App\Http\Data\RegisterCustomerData;
use Src\Customer\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;

class CompleteCustomerProfileCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(CompleteCustomerProfileData $request): \Illuminate\Http\JsonResponse
    {
        //

        return jsonResponse(Response::HTTP_OK,$request->customer);
    }
}
