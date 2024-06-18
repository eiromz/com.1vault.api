<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\KnowYourCustomer;
use App\Models\Profile;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Merchant\Domain\Services\GenerateAccountNumber;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class GenerateAccountNumberQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
    const DEFAULT_SERVICE_FAILURE = 'Ooops failed, account number not created';
    public function __construct(public Customer $customer, public Profile $profile, public KnowYourCustomer $kyc)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $generateAccountService = new GenerateAccountNumber($this->customer, $this->profile, $this->kyc);

            if (! $generateAccountService->payload['requestSuccessful'] || $generateAccountService->payload['responseCode'] === '00') {
                $generateAccountService->notify(self::DEFAULT_SERVICE_FAILURE);
                logExceptionErrorMessage('GenerateAccountNumber-Service', null, $generateAccountService->payload);
                throw new Exception('Requests service was not successful', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
            }

            if($generateAccountService->save()){
                $generateAccountService->notify();
            }

        } catch (Exception|JsonException|FatalRequestException|RequestException $e) {
            logExceptionErrorMessage('GenerateAccountNumberQueue', $e,[],'critical');
        }
    }
}
