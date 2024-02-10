<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Merchant\App\Http\Request\LoginRequest;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthenticateSessionCtrl extends DomainBaseCtrl
{
    public function store(LoginRequest $request): JsonResponse
    {
        return jsonResponse(ResponseAlias::HTTP_OK, [
            'token' => $request->authenticate(),
        ]);
    }
    public function destroy(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return jsonResponse(ResponseAlias::HTTP_OK, [
            'message' => 'You have been logged out of your account!',
        ]);
    }
}
