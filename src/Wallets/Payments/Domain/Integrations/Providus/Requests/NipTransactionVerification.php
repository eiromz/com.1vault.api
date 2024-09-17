<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class NipTransactionVerification extends Request
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected array $payload) {}

    protected function defaultBody(): array
    {
        return $this->payload;
    }

    /**
     * Define the endpoint for the request.
     */
    public function resolveEndpoint(): string
    {
        return '/GetNIPTransactionStatus';
    }
}
