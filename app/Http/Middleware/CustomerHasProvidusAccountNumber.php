<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerHasProvidusAccountNumber
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($request->user()?->profile?->account_number)) {
            abort(400, "You dont have an account number");
        }

        return $next($request);
    }
}
