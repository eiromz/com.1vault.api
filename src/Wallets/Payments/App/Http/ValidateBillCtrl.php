<?php

namespace Src\Wallets\Payments\App\Http;

use App\Exceptions\BaseException;
use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Cart;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetFieldsForBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\ValidateBill;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ValidateBillCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke($bill,Request $request): JsonResponse
    {
        $boolean = match($this->billNeedsValidation($bill)){
            'y' => true,
        };

        if($boolean){
            $this->validateBill($request->all(),$bill);
        }

        $data = Cart::query()->create($request->only(['service_id', 'request_id', 'price', 'customer_id', 'account_id']));

        return jsonResponse(Response::HTTP_OK, $data);
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException|JsonException
     */
    private function validateBill($request,$bill)
    {
        $connector  = new ProvidusBills();
        $request    = new ValidateBill($request,$bill);
        $response = $connector->send($request);
        return $response->json();
    }
    private function billNeedsValidation($bill)
    {
        try {
            $connector  = new ProvidusBills();
            $request    = new GetFieldsForBills($bill);
            $response   = $connector->send($request);

            return $response->collect()?->get('validate');
        }
        catch (Exception $e)
        {
            logExceptionErrorMessage('billsNeedsValidation',$e,[],'critical');
            return jsonResponse(ResponseAlias::HTTP_BAD_GATEWAY,[
                'message' => 'Service not available'
            ]);
        }
    }
}
