<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Src\Merchant\Domain\Mail\VerificationEmail;
use Symfony\Component\HttpFoundation\Response;

class ResendOtpCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:App\Models\Customer,email'],
            ]);

            $customer = $this->getCustomerInfo($request->email);

            $this->otpHasExpired($customer);

            Mail::to($customer->email)->queue(new VerificationEmail($customer->otp));

            return jsonResponse(Response::HTTP_OK, [
                'message' => 'Otp Sent Successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Email already verified',
            ]);
        }
    }

    private function getCustomerInfo($email): Model|Builder
    {
        return Customer::query()->select(['email', 'otp', 'otp_expires_at'])
            ->where('email', $email)
            ->whereNull('email_verified_at')
            ->firstOrFail();
    }

    private function otpHasExpired($customer)
    {
        if ($customer->otp_expires_at->isPast()) {
            $customer->otp = generateOtpCode();
            $customer->otp_expires_at = now()->addMinutes(15);
            $customer->save();
        }
    }
}
