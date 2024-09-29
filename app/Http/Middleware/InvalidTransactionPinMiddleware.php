<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class InvalidTransactionPinMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $check = Hash::check($request?->transaction_pin, $request?->user()?->transaction_pin);

        abort_if(! $check, Response::HTTP_BAD_REQUEST, 'Invalid transaction pin');

        return $next($request);
    }
}
