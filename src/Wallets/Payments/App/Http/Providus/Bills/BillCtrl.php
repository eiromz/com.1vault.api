<?php

namespace Src\Wallets\Payments\App\Http\Providus\Bills;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\JsonResponse;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Src\Template\Application\Exceptions\BaseException;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetFieldsForBills;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BillCtrl extends DomainBaseCtrl
{
    /**
     * @throws FatalRequestException
     * @throws BaseException
     * @throws RequestException
     * @throws JsonException
     */
    public function index($bill): JsonResponse
    {
        try {
            $connector = new ProvidusBills;
            $request = new GetFieldsForBills($bill);
            $response = $connector->send($request);
            $data = $response->json();

            return jsonResponse($response->status(), $data);
        } catch (\Exception $e) {
            logExceptionErrorMessage('BillCtrl::Index', $e, [], 'critical');

            return jsonResponse(ResponseAlias::HTTP_OK, [
                'message' => 'Service not available',
            ]);
        }
    }
}

//provider is a list of services and their categories
