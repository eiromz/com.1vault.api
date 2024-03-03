<?php

namespace App\Http\Integrations\Providus\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class PayBill extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/example';
    }
}
