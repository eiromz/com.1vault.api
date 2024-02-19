<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Wallets\Payments\App\Enum\ErrorMessages;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusRestApi;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetNipAccount;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\NipFundTransfer;
use Symfony\Component\HttpFoundation\Response;

class TransferCtrl extends DomainBaseCtrl
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            "narration" => ['nullable'],
            "currencyCode" => ['required'],
            "beneficiaryBank" => ['required'],
            "transactionAmount" => ['required'],
            "beneficiaryAccountName" => ['required'],
            "beneficiaryAccountNumber"=>['required']
        ]);

        $request->merge([
            'userName' => config('providus-bank.rest_api_username'),
            'password' => config('providus-bank.rest_api_password'),
            "sourceAccountName" => auth()->user()->profile->fullname,
            "transactionReference" => generateProvidusTransactionRef()
        ]);

        $connector  = new ProvidusRestApi();
        $request    = new NipFundTransfer($request->all());
        $response   = $connector->send($request);

        dd($response->json());

        $data = $response->json();

        if($response->status() !== 200 && !isset($data['responseCode'])) {
            throw new BaseException(ErrorMessages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        if($data['responseCode'] !== "00"){
            throw new BaseException(ErrorMessages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        return jsonResponse($response->status(),$response->json());
    }
}
