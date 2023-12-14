<?php

namespace App\Http\Middleware;

use App\Models\KnowYourCustomer;
use Aws\CustomerProfiles\Exception\CustomerProfilesException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KycCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user()->load('knowYourCustomer');

        if(is_null($user->knowYourCustomer)) {
            throw new Exception('Please complete your profile verification before proceeding', Response::HTTP_BAD_REQUEST);
        }

        if(in_array($user->knowYourCustomer->status,KnowYourCustomer::STATUS_CODES)){
            throw new Exception('You have not been authorized to use this service', Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
