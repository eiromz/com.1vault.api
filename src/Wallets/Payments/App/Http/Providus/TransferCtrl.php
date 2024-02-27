<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use App\Jobs\GenerateAccountNumberQueue;
use App\Jobs\SaveBeneficiaryQueue;
use Exception;
use Illuminate\Http\JsonResponse;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Wallets\Payments\App\Enum\Messages;
use Src\Wallets\Payments\App\Requests\NipTransferRequest;
use Src\Wallets\Payments\Domain\Actions\GetAccountInstance;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusRestApi;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\NipFundTransfer;
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

        $nip = (new NipTransferService($request))->callExtServer()->callExtServerFailed();

        return jsonResponse(Response::HTTP_OK, $source->journal);
    }
}
