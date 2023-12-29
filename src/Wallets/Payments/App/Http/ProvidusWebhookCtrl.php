<?php

namespace Src\Wallets\Payments\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Wallets\Payments\Domain\Services\ProvidusCreditService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProvidusWebhookCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'accountNumber' => ['required','exists:App\Models\Profile,account_number']
        ]);

        $service = new ProvidusCreditService($request);

        $service->isDuplicate();

        return jsonResponse(ResponseAlias::HTTP_OK,[
            "requestSuccessful" => true,
            "sessionId"         => $request->sessionId,
            "responseMessage"   => "success",
            "responseCode"      => "00"
        ]);
    }
}
