<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer as Staff;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Src\Merchant\App\Http\Request\CreateStaffRequest;

class StaffCtrl extends DomainBaseCtrl
{
    /**
     * @throws Exception
     */
    public function index(): JsonResponse
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
        $request->validated();
        $request->createStaff();
        $request->createStaffProfile();
        return jsonResponse(Response::HTTP_OK,$request->only(['firstname','lastname','email']));
    }

    public function destroy($staff, Request $request): JsonResponse
    {
        $request
            ->merge(['staff' => $staff])
            ->validate([
                'staff' => ['required','exists:App\Models\Customer,id']
            ]);
//        $request->validate([
//            'staff' => ['required','exists:App\Models\Customer,id']
//        ]);

        $staff = Staff::query()
            ->where('ACCOUNTID','=',auth()->user()->ACCOUNTID)
            ->where('id','=',$request->staff)
            ->first();

        if(!$staff->delete()){
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Staff not removed'
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Staff removed'
        ]);
    }
}
