<?php

namespace Src\Wallets\Payments\App\Http;

use App\Exceptions\BaseException;
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
        $request->validate([
            'inputs' => 'required|array',
        ]);

        $connector = new ProvidusBills;
        $response = $connector->send(new ValidateBill($request->all(), $bill));
        if ($response->failed()) {
            throw new BaseException(
                'We could not process this bill at this time',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $response->json();

        //return jsonResponse(Response::HTTP_OK, $data);
    }
}
