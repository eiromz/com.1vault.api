<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Integrations\Providus\ProvidusBills;
use Src\Wallets\Payments\Domain\Integrations\Providus\Requests\ValidateBill;
use Symfony\Component\HttpFoundation\Response;

class ValidateBillCtrl extends DomainBaseCtrl
{

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
            logger('ValidateBillLogResponse', [$connector->debug()]);

            $response = $connector->send(new ValidateBill(['inputs' => $request->inputs], $request->bill));

            return jsonResponse(Response::HTTP_OK, $response->json());
        } catch (Exception $e) {
            logger('ValidateBillCtrlException', [$e->getMessage()]);

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
