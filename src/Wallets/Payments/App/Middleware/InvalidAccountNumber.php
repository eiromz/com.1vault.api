<?php

namespace Src\Wallets\Payments\App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvalidAccountNumber
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->profile->account_number === $request->account_number) {
            abort(400, "You can't perform this transaction");
        }

        if ($request->user()->profile->account_number === $request->beneficiaryAccountNumber) {
            abort(400, "You can't perform this transaction");
        }

        return $next($request);
    }
}
