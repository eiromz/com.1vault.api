<?php

namespace Src\Merchant\Domain\Services;

use App\Jobs\SendFireBaseNotificationQueue;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Merchant\Domain\Integrations\Providus\ProvidusConnector;
use Src\Merchant\Domain\Integrations\Providus\Requests\ReserveAccountNumberRequest;
use function Pest\Laravel\json;

class GenerateAccountNumber
{
    public array $payload;
    public array $notification = [
        'title' => 'Account Number Generation',
        'body' => 'Your account number has been generated',
    ];
    private $connector;

    /**
     * @throws FatalRequestException
     * @throws JsonException
     * @throws RequestException
     */
    public function __construct(public $customer, public $profile, public $kyc)
    {
        $this->payload = $this->sendRequest();
        $this->connector = new ProvidusConnector;
    }
    public function save()
    {
        $this->profile->account_number = $this->payload['account_number'];
        return $this->profile->save();
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
        $request = new ReserveAccountNumberRequest([
            'account_name' => $this->profile->lastname,
            'bvn' => $this->kyc->bvn,
        ]);

        $response = $this->connector->send($request);

        return $response->json();
    }
    public function notify($defaultMessage=null): void
    {
        if(!is_null($defaultMessage)){
            $this->notification['body'] = $defaultMessage;
        }
        SendFireBaseNotificationQueue::dispatch($this->customer->firebase_token, $this->notification)->delay(now()->addMinute());
    }
}
