<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer as Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Src\Merchant\App\Http\Request\CreateStaffRequest;

class StaffCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function index()
    {
        $staffs = Staff::query()
            ->where('ACCOUNTID', '=',$this->customer->ACCOUNTID)
            ->where('is_member', '=',true)
            ->where('status','=',1)
            ->get();
        return jsonResponse(Response::HTTP_OK, $staffs);
    }

    public function store(CreateStaffRequest $request): JsonResponse
    {
        dd($request->all());
        //Customer
        //Profile
        return \jsonResponse(Response::HTTP_OK,$staff);
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
