<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCatgories extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/categories';
    }
}
