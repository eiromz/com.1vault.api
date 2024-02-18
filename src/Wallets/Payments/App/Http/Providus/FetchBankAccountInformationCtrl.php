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
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetNipBanks;
use Symfony\Component\HttpFoundation\Response;

class FetchBankAccountInformationCtrl extends DomainBaseCtrl
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws BaseException
     * @throws \JsonException
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'accountNumber' => ['required'],
                'beneficiaryBank' => ['required'],
            ]);

            $request->merge([
                'userName' => config('providus-bank.rest_api_username'),
                'password' => config('providus-bank.rest_api_password'),
            ]);

            $connector = new ProvidusRestApi();
            $request = new GetNipAccount($request->all());
            $response = $connector->send($request);

            $data = $response->json();

            if($response->status() !== 200 && !isset($data['responseCode'])){
                throw new BaseException(
                    ErrorMessages::FETCH_ACCOUNT_DETAILS_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            return jsonResponse($response->status(),$response->json());
        }
        catch (\Exception $e){
            logExceptionErrorMessage('FetchBankAccountInformationCtrl',$e,[],'critical');
            return jsonResponse(Response::HTTP_BAD_REQUEST,[
                'message' => ErrorMessages::FETCH_ACCOUNT_DETAILS_FAILED->value
            ]);
        }
    }
}
