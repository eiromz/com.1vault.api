<?php

namespace Src\Merchant\Domain\Integrations\Providus\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ReserveAccountNumberRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    public function __construct(
        protected array $payload
    ) {}

    protected function defaultBody(): array
    {
        return $this->payload;
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/PiPCreateReservedAccountNumber';
    }
}
