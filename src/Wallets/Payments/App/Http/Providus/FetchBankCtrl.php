<?php

namespace Src\Wallets\Payments\App\Http\Providus;

use App\Http\Controllers\DomainBaseCtrl;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Template\Application\Exceptions\BaseException;
use Src\Wallets\Payments\App\Enum\Messages;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusRestApi;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetNipBanks;
use Symfony\Component\HttpFoundation\Response;

class FetchBankCtrl extends DomainBaseCtrl
{
    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws BaseException
     * @throws \JsonException
     */
    public function __invoke()
    {
        $connector = new ProvidusRestApi;
        $request = new GetNipBanks;
        $response = $connector->send($request);

        if ($response->status() !== 200) {
            throw new BaseException(
                Messages::FETCH_BANK_LIST_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        $data = $response->json();

        if ($data['responseCode'] !== '00') {
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        return \jsonResponse($response->status(), $data);
    }
}
