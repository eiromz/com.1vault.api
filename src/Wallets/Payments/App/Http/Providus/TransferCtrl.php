<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     * @throws \Exception
     */
    public function __invoke(NipTransferRequest $request): JsonResponse
    {
       try {
           $request->execute();
           $connector = new ProvidusRestApi();
           $nipRequest = new NipFundTransfer($request->all());
           $response = $connector->send($nipRequest);

           $source = new JournalWalletDebitService(
               GetAccountInstance::getActiveInstance(auth()->user()->profile),
               $request
           );

           $source->validateTransactionPin();

           $source->checkBalance()->debit()->notify()->updateBalanceQueue();

           $data = $response->json();

        if ($response->status() !== 200 && !isset($data['responseCode'])) {
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($data['responseCode'] !== "00") {
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        return jsonResponse($response->status(), $data);
       } catch(\Exception $e) {
           return jsonResponse(Response::HTTP_BAD_REQUEST,[
               'message' => 'We could not fulfil this transaction'
           ]);
       }
    }
}
