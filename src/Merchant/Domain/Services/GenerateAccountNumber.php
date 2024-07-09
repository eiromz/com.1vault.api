<?php

namespace Src\Merchant\Domain\Services;

use App\Jobs\SendFireBaseNotificationQueue;
use App\Models\KnowYourCustomer;
use App\Models\Profile;
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
    public object $connector;

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
        $this->kyc = KnowYourCustomer::query()->find($this->kyc->id);
        $this->profile =  Profile::query()->find($this->profile->id);

        $this->profile->fill([
            'account_number' => $this->payload['account_number']
        ])->save();

        $this->kyc->fill([
            "date_attempted_account_generation" => now(),
            "bvn_validation_payload" => $this->payload,
        ])->save();
    }

    /**
     * @return mixed
     * @throws FatalRequestException
     * @throws JsonException
     * @throws RequestException
     */
    public function sendRequest(): mixed
    {
        $params = [
            'account_name' => $this->profile->fullname,
            'bvn' => $this->kyc->bvn,
        ];

        $this->connector = new ProvidusConnector;

        $payload = new ReserveAccountNumberRequest($params);

        logger('error',$params);

        $response = $this->connector->send($payload);

        return $response->json();
    }
    public function notify($defaultMessage=null): void
    {
        if(!is_null($defaultMessage)) {
            $this->notification['body'] = $defaultMessage;
        }
        SendFireBaseNotificationQueue::dispatch($this->customer->firebase_token, $this->notification)->delay(now()->addMinute());
    }
}
