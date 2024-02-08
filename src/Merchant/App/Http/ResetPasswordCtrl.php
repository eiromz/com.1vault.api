<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Src\Merchant\Domain\Mail\ResetPasswordMail;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordCtrl extends DomainBaseCtrl
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:customers,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->uncompromised()],
            'password_confirmation' => ['required'],
        ]);

        $customer = Customer::query()->where('email', $request->email)->firstOrFail();

        $customer->password = Hash::make($request->password);

        if (! $customer->save()) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'We could not update your password',
            ]);
        }

        Mail::to($customer->email)->queue(new ResetPasswordMail($customer->email));

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Successfully Updated Your Password',
        ]);
    }
}
