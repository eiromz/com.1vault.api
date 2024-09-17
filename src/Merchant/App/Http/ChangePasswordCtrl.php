<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Src\Template\Application\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'current_password' => ['required', Password::min(8)->letters()->mixedCase()->uncompromised()],
                'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->uncompromised()],
                'password_confirmation' => ['required'],
            ]);

            if (! Hash::check($request->current_password, auth()->user()->getAuthPassword())) {
                throw new BaseException(
                    'Failed to update your password',
                    Response::HTTP_BAD_REQUEST
                );
            }

            $customer = Customer::query()->find(auth()->user()->id);

            $customer->password = Hash::make($request->password);

            if ($customer->save()) {
                return jsonResponse(Response::HTTP_OK, [
                    'message' => 'Successfully Updated Your Password',
                ]);
            }

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update your password',
            ]);

        } catch (Exception $e) {
            logExceptionErrorMessage('ChangePasswordCtrl', $e);

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update your password',
            ]);
        }
    }
}
