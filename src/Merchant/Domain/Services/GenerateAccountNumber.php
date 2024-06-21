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
    public $connector;

    /**
     * @throws FatalRequestException
     * @throws JsonException
     * @throws RequestException
     */
    public function __construct(public $customer, public $profile, public $kyc)
    {
        $this->payload = $this->sendRequest($connector = new ProvidusConnector);
    }
    public function save()
    {
        $kyc = KnowYourCustomer::query()->find($this->kyc->id);
        $profile =  Profile::query()->find($this->profile->id);

        $profile->fill([
            'account_number' => $this->payload['account_number']
        ])->save();

        $kyc->fill([
            "date_attempted_account_generation" => now(),
            "bvn_validation_payload" => $this->payload,
        ])->save();
    }
    /**
     * @param $connector
     * @return mixed
     */
    public function sendRequest($connector): mixed
    {
        $request = new ReserveAccountNumberRequest([
            'account_name' => $this->profile->lastname,
            'bvn' => $this->kyc->bvn,
        ]);

        logger('error',[
            'account_name'  => $this->profile->lastname,
            'bvn'           => $this->kyc->bvn,
        ]);

        $response = $connector->send($request);

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
