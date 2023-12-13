<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Src\Customer\App\Http\Data\CompleteCustomerProfileData;
use Symfony\Component\HttpFoundation\Response;

class KnowYourCustomerCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function __invoke(CompleteCustomerProfileData $request): \Illuminate\Http\JsonResponse
    {
        $this->customer = auth()->user();
        $request->toArray();
        $request->execute($this->customer);

        return jsonResponse(Response::HTTP_OK, $this->customer->load('profile'));
    }
}
