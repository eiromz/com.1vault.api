<?php

namespace Src\Wallets\Payments\App\Http\Providus\Bills;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\GetFieldsForBills;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BillCtrl extends DomainBaseCtrl
{
    public function index($bill): JsonResponse
    {
        try {
            $connector = new ProvidusBills;
            $request = new GetFieldsForBills($bill);
            $response = $connector->send($request);

            return jsonResponse($response->status(), $response->json());
        } catch (Exception $e) {
            logExceptionErrorMessage('BillCtrl::Index', $e, [], 'critical');

            return jsonResponse(ResponseAlias::HTTP_OK, [
                'message' => 'Service not available',
            ]);
        }
    }
}

//provider is a list of services and their categories
