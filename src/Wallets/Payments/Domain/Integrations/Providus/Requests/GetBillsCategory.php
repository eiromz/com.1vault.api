<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetBillsCategory extends Request
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
        return "/bill/byCategoryId/{$this->category}";
    }

    public function __construct(protected string $category) {}
}
