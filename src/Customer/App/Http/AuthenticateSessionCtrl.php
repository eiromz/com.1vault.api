<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Customer\App\Http\Request\LoginRequest;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthenticateSessionCtrl extends DomainBaseCtrl
{
    /**
     * Create a new session
     */
    public function store(LoginRequest $request)
    {
        return jsonResponse(ResponseAlias::HTTP_OK, [
            'token' => $request->authenticate(),
        ]);
    }

    /**
     * Destroy an authenticated session
     */
    public function destroy(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return jsonResponse(ResponseAlias::HTTP_OK, [
            'message' => 'You have been logged out of your account!',
        ]);
    }
}
