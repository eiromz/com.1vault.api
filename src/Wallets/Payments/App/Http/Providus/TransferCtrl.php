<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Template\Application\Exceptions\BaseException;
use Src\Wallets\Payments\App\Requests\NipTransferRequest;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Services\JournalWalletDebitService;
use Src\Wallets\Payments\Domain\Services\NipTransferService;
use Symfony\Component\HttpFoundation\Response;

class TransferCtrl extends DomainBaseCtrl
{
    /**
     * @throws FatalRequestException
     * @throws BaseException
     * @throws RequestException
     * @throws JsonException
     * @throws Exception
     */
    public function __invoke(NipTransferRequest $request): JsonResponse
    {
        $request->execute();

        $source = new JournalWalletDebitService(
            GetAccountInstance::getActiveInstance(auth()->user()->profile),
            $request
        );

        $source->validateTransactionPin();

        $source->checkBalance()->debit()->notify()->saveBeneficiary()->updateBalanceQueue();

        (new NipTransferService($request))->callExtServer()->callExtServerFailed();

        //if querying the transaction status fails then the requery service should automatically reverse the transaction to the
        //customers wallets
        //receive the id in a job and then dump the data here
        //QueryTransactionStatusJob::dispatch()->delay(now()->minutes(15));

        return jsonResponse(Response::HTTP_OK, $source->journal);
    }
}
