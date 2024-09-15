<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetFieldsForBills extends Request
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
        return "/field/byBillId/{$this->bill}";
    }

    public function __construct(protected string $bill) {}
}
