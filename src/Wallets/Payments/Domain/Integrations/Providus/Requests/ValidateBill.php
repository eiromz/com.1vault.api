<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ValidateBill extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    public function __construct(protected array $payload, protected string $bill) {}

    protected function defaultBody(): array
    {
        logger()->info('ValidateBillPayload',$this->payload);
        return $this->payload;
    }

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/validate/{$this->bill}/customer";
    }
}
