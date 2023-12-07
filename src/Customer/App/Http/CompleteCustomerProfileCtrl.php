<?php

namespace Src\Customer\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Src\Customer\App\Http\Data\RegisterCustomerData;
use Src\Customer\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;

class CompleteCustomerProfileCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'first_name'    => ['required','string','min:3'],
            'last_name'     => ['required','string','min:3']
        ]);

        return jsonResponse(Response::HTTP_OK,$request->customer);
    }
}
