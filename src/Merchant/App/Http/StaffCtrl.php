<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use App\Models\Customer as Staff;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Merchant\App\Http\Resources\StaffResource;
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
        $request->sendWelcomeEmail();
        return jsonResponse(Response::HTTP_OK,new StaffResource($request->staff));
    }
    public function update($staff,Request $request): JsonResponse
    {
        $request->merge(['staff' => $staff])->validate([
            'staff' => ['required','exists:App\Models\Customer,id'],
            'firstname'    => ['required','string','min:3'],
            'lastname'      => ['required','string','min:3'],
            'email'         => ['nullable','unique:App\Models\Customer,email'],
        ]);

        $staffObject = Staff::query()->with('profile')->findOrFail($staff);

        if(auth()->user()->ACCOUNTID !== $staffObject->ACCOUNTID){
            return jsonResponse(Response::HTTP_UNAUTHORIZED, [
                'message' => 'You cannot modify this user'
            ]);
        }

        $profile = $staffObject->profile->fill($request->only(['firstname','lastname']));

        $saved = $staffObject->fill($request->only(['email']))->save() && $profile->save();

        if(!$saved){
            return jsonResponse(Response::HTTP_UNAUTHORIZED, [
                'message' => 'Failed to modify profile'
            ]);
        }

        return jsonResponse(Response::HTTP_OK, new StaffResource($staffObject));
    }
    public function destroy($staff, Request $request): JsonResponse
    {
        $request->merge(['staff' => $staff])->validate([
                'staff' => ['required','exists:App\Models\Customer,id']
            ]);

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
