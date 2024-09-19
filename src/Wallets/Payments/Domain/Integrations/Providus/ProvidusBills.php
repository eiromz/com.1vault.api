<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class ProvidusBills extends Connector
{
    use AcceptsJson, AlwaysThrowOnErrors;

    private string $prod = 'providus-bank.base_url';

    private string $stag = 'providus-bank.base_url_bills';

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {

        $base_url_string = (app()->environment('production')) ? $this->prod : $this->stag;

        return (string) config($base_url_string);
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
