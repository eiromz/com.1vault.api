<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Customer\App\Http\Resources\ProfileResource;
use Symfony\Component\HttpFoundation\Response;

class ProfileCtrl extends DomainBaseCtrl
{
    /**
     * TODO refactor this method using form request
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $profile = Profile::where('customer_id',auth()->user()->id)->with(['state','customer'])->first();

        return jsonResponse(Response::HTTP_OK,
            new ProfileResource($profile)
        );
    }

    public function update(Request  $request): JsonResponse
    {
        $request->validate([
            'firstname'         =>  ['nullable','string'],
            'lastname'          =>  ['nullable','string'],
            'phone_number'      =>  ['nullable','string'],
            'email'             =>  ['nullable','unique:App\Models\Customer,email'],
            'firebase_token'    =>  ['nullable','string'],
            'business_name'     =>  ['nullable','string'],
            'business_physical_address' =>  ['nullable','string'],
            'business_zip_code' =>  ['nullable','string'],
            'business_logo'     =>  ['nullable','string'],
            'image'             =>  ['nullable','string'],
        ]);

        $emailIsSame = $request->email !== auth()->user()->email;


        $customer = Customer::findOrFail($request->user()->id);
        $customer->fill($request->only(['email','phone_number','firebase_token']));

        if($emailIsSame && $customer->isDirty('email')){
            $customer->email_verified_at = null;
        }

        if(!$customer->save()){
            return jsonResponse(Response::HTTP_BAD_REQUEST,[
                'message' => 'Profile update failed'
            ]);
        }

        $profile = Profile::where('id',auth()->user()->profile->id)->with('customer')->firstOrFail();

        $profile->fill($request->only([
            'firstname','lastname','business_name','business_physical_address',
            'business_logo', 'business_zip_code'
        ]));

        if(!$profile->save()){
            return jsonResponse(Response::HTTP_BAD_REQUEST,[
                'message' => 'Profile update failed'
            ]);
        }

        return jsonResponse(Response::HTTP_OK,
            new ProfileResource($profile));
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request): JsonResponse
    {
        auth()->user()->delete();

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Account deletion successful!'
        ]);
    }
}
