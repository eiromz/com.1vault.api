<?php

namespace Src\Merchant\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Src\Merchant\App\Http\Data\RegisterCustomerData;
use Src\Merchant\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer as Merchant;

class RegisterCustomerCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(RegisterCustomerData $request): JsonResponse
    {
        if($merchantExists = $this->customerExistsAndIsNotVerified($request->email)){
            if(!is_null($merchantExists->email_verified_at)) {
                throw new BaseException('Email has already been taken', Response::HTTP_BAD_REQUEST);
            }

            Mail::to($merchantExists->email)->queue(new VerificationEmail($merchantExists->otp));
            return jsonResponse(Response::HTTP_OK, $merchantExists);
        }

        $request->toArray();
        $request->newCustomerInstance()->save();

        Mail::to($request->customer->email)->queue(new VerificationEmail($request->customer->otp));

        return jsonResponse(Response::HTTP_OK, $request->customer);
    }

    private function customerExistsAndIsNotVerified($email): Model|Builder|null
    {
        return Merchant::query()
            ->where('email','=',$email)
            ->first();
    }
}
