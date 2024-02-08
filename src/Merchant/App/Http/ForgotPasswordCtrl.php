<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Src\Merchant\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordCtrl extends DomainBaseCtrl
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:customers,email'],
        ]);

        $customer = Customer::query()->where('email', $request->email)->firstOrFail();

        if ($customer->otp_expires_at->isPast()) {
            $customer->otp = generateOtpCode();
            $customer->otp_expires_at = now()->addMinutes(15);
            $customer->save();
        }

        Mail::to($customer->email)->queue(new VerificationEmail($customer->otp));

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Otp has been sent to your registered email please check',
        ]);
    }
}
