<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Merchant\App\Http\Data\CompleteCustomerProfileData;
use Symfony\Component\HttpFoundation\Response;

class CompleteCustomerProfileCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(CompleteCustomerProfileData $request): JsonResponse
    {
        $this->customer = auth()->user();
        $request->toArray();

        $request->execute($this->customer);

        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
