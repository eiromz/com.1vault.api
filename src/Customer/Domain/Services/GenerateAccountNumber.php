<?php

namespace Src\Customer\Domain\Services;

use App\Models\Customer;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Customer\Domain\Integrations\Providus\ProvidusConnector;
use Src\Customer\Domain\Integrations\Providus\Requests\ReserveAccountNumberRequest;

class GenerateAccountNumber
{
    public array $payload;

    /**
     * @throws FatalRequestException
     * @throws JsonException
     * @throws RequestException
     */
    public function __construct(public $customer, public $profile, public $kyc)
    {
        $this->payload = $this->sendRequest();
    }

    public function save(): void
    {
        $this->profile->account_number = $this->payload['account_number'];
        $this->profile->save();
    }

    /**
     * @return mixed|mixed[]
     *
     * @throws FatalRequestException
     * @throws JsonException
     * @throws RequestException
     */
    public function sendRequest(): mixed
    {
        $connector = new ProvidusConnector;
        $request = new ReserveAccountNumberRequest([
            'account_name' => $this->profile->lastname,
            'bvn' => $this->kyc->bvn,

        ]);

        $response = $connector->send($request);

        return $response->json();
    }

    //send email to notify customer of the new account number
}
