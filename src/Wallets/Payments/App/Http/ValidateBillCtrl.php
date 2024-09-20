<?php

namespace Src\Wallets\Payments\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Saloon\Exceptions\Request\FatalRequestException;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\ValidateBill;
use Symfony\Component\HttpFoundation\Response;

class ValidateBillCtrl extends DomainBaseCtrl
{
    private $response;
    /**
     * @throws Exception
     */
    public function __invoke($bill, Request $request): JsonResponse
    {
        try {
            $request->merge(['bill' => $bill]);

            $request->validate([
                'inputs' => 'required|array',
                'bill' => 'required',
            ]);

            $connector = new ProvidusBills;
            $this->response = $connector->send(new ValidateBill($request->all(), $bill));

            if ($this->response->failed()) {
                logger()->error('ValidateBillCtrl',[$this->response->body()]);
                throw new BaseException(
                    'Invalid Customer Details',
                    Response::HTTP_BAD_REQUEST
                );
            }

            return jsonResponse(Response::HTTP_OK, $this->response->json());
        }catch (Exception|FatalRequestException $e){
            logger('ValidateBillCtrl',[$e->getMessage()]);

            return jsonResponse(Response::HTTP_BAD_REQUEST,[
                'message' => 'We could not process your request at this time'
            ]);
        }
    }
}
