<?php

namespace Src\Accounting\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Accounting\App\Requests\CreateBusinessInformationRequest;
use Symfony\Component\HttpFoundation\Response;

class BusinsessInformationCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function create(CreateBusinessInformationRequest $request): JsonResponse
    {
        $request->validated();
        //create a business information from the api data
        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
