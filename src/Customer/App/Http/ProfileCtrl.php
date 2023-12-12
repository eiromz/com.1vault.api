<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Exception;
use Illuminate\Http\Request;
use Src\Customer\App\Http\Data\CompleteCustomerProfileData;
use Symfony\Component\HttpFoundation\Response;

class ProfileCtrl extends DomainBaseCtrl
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return jsonResponse(Response::HTTP_OK,
            auth()->user()->load('profile')
        );
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        auth()->user()->delete();

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Account deletion successful!'
        ]);
    }
}
