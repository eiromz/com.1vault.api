<?php

namespace Src\Wallets\Payments\App\Http\Providus\Bills;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Wallets\Payments\App\Enum\Messages;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetCatgories;
use Illuminate\Http\JsonResponse;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Symfony\Component\HttpFoundation\Response;

class BillCtrl extends DomainBaseCtrl
{

    /**
     * @throws FatalRequestException
     * @throws BaseException
     * @throws RequestException
     * @throws JsonException
     */
    public function index(): JsonResponse
    {
        $connector  = new ProvidusBills();
        $request    = new GetCatgories();
        $response = $connector->send($request);

        if (!$response->ok()) {
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        if($response->collect()->isEmpty()){
            throw new BaseException(Messages::TRANSACTION_FAILED->value,
                Response::HTTP_BAD_REQUEST
            );
        }

        $data = $response->collect()->filter(fn($array) => !in_array($array['name'],$this->excludeBills));

        return jsonResponse($response->status(),$data);
    }

}
