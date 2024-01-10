<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Customer\App\Http\Data\CompleteCustomerProfileData;
use Symfony\Component\HttpFoundation\Response;

class BusinsessInformationCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function create(CompleteCustomerProfileData $request): JsonResponse
    {
        $this->customer = auth()->user();
        $request->toArray();

        $request->execute($this->customer);

        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }

    public function destroy(): JsonResponse
    {
        return \jsonResponse();
    }
}
