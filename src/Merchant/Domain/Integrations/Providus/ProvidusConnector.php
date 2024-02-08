<?php

namespace Src\Merchant\Domain\Integrations\Providus;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class ProvidusConnector extends Connector
{
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return (string) config('providus-bank.base_url');
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Client-Id' => config('providus-bank.clientId'),
            'X-Auth-Signature' => config('providus-bank.x-auth-signature'),
        ];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
