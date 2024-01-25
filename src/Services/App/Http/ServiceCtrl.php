<?php

namespace Src\Services\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\JsonResponse;
use Src\Services\App\Requests\ServiceRequest;
use Symfony\Component\HttpFoundation\Response;

class ServiceCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function store(ServiceRequest $request): JsonResponse
    {
        //register_business_request
        //register_business_llc_request
        //pos_request
        $request->validated([
            'action' => ['required',
                'in:register_business_request,register_business_llc_request,pos_request'
            ]
        ]);

        return jsonResponse(Response::HTTP_OK, $data);
    }
}
