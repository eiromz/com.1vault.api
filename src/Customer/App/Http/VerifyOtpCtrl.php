<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Src\Customer\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtpCtrl extends DomainBaseCtrl
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => ['required','email','exists:customers,email'],
            'otp'   => ['required','exists:customers,otp','size:6'],
        ]);

        $customer = Customer::query()
            ->where('email',$request->email)
            ->where('otp', $request->otp)
            ->firstOrFail();

        $this->otpHasExpired($customer);

        if($customer->otp_expires_at->isPast()) {
            return jsonResponse(Response::HTTP_OK, [
                'message' => "Otp expired, please check your registered email for a new otp."
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => "Successfully Verified Otp"
        ]);
    }



    public function otpHasExpired($customer) : void
    {
        if($customer->otp_expires_at->isPast()) {
            $customer->otp = generateOtpCode();
            $customer->otp_expires_at = now()->addMinutes(15);
            $customer->save();

            Mail::to($customer->email)->queue(new VerificationEmail($customer->otp));
        }
    }
}
