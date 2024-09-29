<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class ProvidusBills extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors;

    private string $base_url = 'providus-bank.base_url_bills';

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return (string) config($this->base_url);
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
