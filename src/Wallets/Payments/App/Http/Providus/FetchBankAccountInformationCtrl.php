<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Template\Application\Exceptions\BaseException;
use Src\Wallets\Payments\App\Enum\Messages;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusRestApi;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetNipAccount;
use Symfony\Component\HttpFoundation\Response;

class FetchBankAccountInformationCtrl extends DomainBaseCtrl
{
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

            $connector = new ProvidusRestApi;
            $request = new GetNipAccount($request->all());
            $response = $connector->send($request);

            $data = $response->json();

            if ($response->status() !== 200 && ! isset($data['responseCode'])) {
                throw new BaseException(Messages::FETCH_ACCOUNT_DETAILS_FAILED->value,
                    Response::HTTP_BAD_REQUEST
                );
            }

            return jsonResponse($response->status(), $response->json());
        } catch (\Exception $e) {
            logExceptionErrorMessage('FetchBankAccountInformationCtrl', $e, [], 'critical');

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => Messages::FETCH_ACCOUNT_DETAILS_FAILED->value,
            ]);
        }
    }
}
