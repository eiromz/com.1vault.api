<?php

namespace Src\Merchant\Domain\Services;

use App\Jobs\SendFireBaseNotificationQueue;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Merchant\Domain\Integrations\Providus\ProvidusConnector;
use Src\Merchant\Domain\Integrations\Providus\Requests\ReserveAccountNumberRequest;

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
        if (! $this->kyc->status) {
            return;
        }

        $connector = new ProvidusConnector;
        $request = new ReserveAccountNumberRequest([
            'account_name' => $this->profile->lastname,
            'bvn' => $this->kyc->bvn,
        ]);

        $response = $connector->send($request);

        if ($response->failed()) {
            logExceptionErrorMessage('GenerateAccountNumberService', null, [
                'message' => 'Failed to generate account number for user',
            ]);
        }

        $notification = [
            'title' => 'Account Number Generation',
            'body' => 'Your account number has been generated',
        ];

        SendFireBaseNotificationQueue::dispatch($this->customer->firebase_token, $notification)->delay(now()->addMinute());

        return $response->json();
    }
}
