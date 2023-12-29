<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
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
                throw new Exception(
                    'Failed to update your password',
                    Response::HTTP_BAD_REQUEST
                );
            }

            $request->user()->fill([
                'password' => Hash::make($request->password),
            ]);

            $request->user()->save();

            return jsonResponse(Response::HTTP_OK, [
                'message' => 'Successfully Updated Your Password',
            ]);
        } catch (Exception $e) {
            logExceptionErrorMessage('ChangePasswordCtrl', $e);

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update your password',
            ]);
        }
    }
}
