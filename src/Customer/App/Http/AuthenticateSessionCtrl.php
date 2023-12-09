<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Src\Customer\App\Http\Request\LoginRequest;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthenticateSessionCtrl extends DomainBaseCtrl
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $token = auth()->user()->createToken($name,Customer::OWNER_ABILITIES)->plainTextToken;

        return jsonResponse(ResponseAlias::HTTP_OK,'Welcome');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
