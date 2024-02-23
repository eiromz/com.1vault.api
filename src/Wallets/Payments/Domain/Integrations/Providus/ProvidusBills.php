<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class ProvidusBills extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return (string)config('providus-bank.base_url_bills');
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
