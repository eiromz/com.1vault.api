<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Src\Customer\App\Http\Data\CompleteCustomerProfileData;
use Src\Customer\App\Http\Data\ProfileData;
use Src\Customer\App\Http\Resources\ProfileResource;
use Symfony\Component\HttpFoundation\Response;

class ProfileCtrl extends DomainBaseCtrl
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $profile = Profile::where('customer_id',auth()->user()->id)->with(['state','customer'])->first();

        return jsonResponse(Response::HTTP_OK,
            new ProfileResource($profile)
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
