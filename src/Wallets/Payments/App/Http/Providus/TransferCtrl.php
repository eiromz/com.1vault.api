<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
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
        try {
            $request->execute();
            $connector = new ProvidusRestApi();
            $nipRequest = new NipFundTransfer($request->only([
                'transactionAmount', 'beneficiaryAccountName', 'beneficiaryAccountNumber',
                'beneficiaryBank', 'currencyCode', 'narration', 'userName', 'password', 'sourceAccountName', 'transactionReference',
            ]));

            $source = new JournalWalletDebitService(
                GetAccountInstance::getActiveInstance(auth()->user()->profile),
                $request
            );

            $source->validateTransactionPin();

            $source->checkBalance()->debit()->notify()->updateBalanceQueue();

            //TODO : this restrictions should be added to the

            $response = $connector->send($nipRequest);

            $data = $response->json();

            if ($response->status() !== 200 && ! isset($data['responseCode'])) {
                throw new BaseException(Messages::TRANSACTION_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            if ($data['responseCode'] !== '00') {
                throw new BaseException(Messages::TRANSACTION_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            //TODO : add restrictions here for checking transactions carried out in a day
            //TODO : transactions over a certain amount should be notified to the admin and kept in a loop

            return jsonResponse($response->status(), $data);
        } catch (Exception $e) {
            logExceptionErrorMessage('TransferCtrl', $e, [], 'critical');

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => Messages::TRANSACTION_FAILED->value,
            ]);
        }
    }
}
