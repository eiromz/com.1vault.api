<?php

namespace Src\Wallets\Payments\Domain\Integrations\Providus\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetNipBanks extends Request
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
        return '/GetNIPBanks';
    }
}


//nip/getbanks
//nip/tr

//providus/nip/getbanks
//providus/nip/transfer
