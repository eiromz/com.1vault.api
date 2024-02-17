<?php

namespace Src\Merchant\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Merchant\App\Http\Resources\CustomerResource;
use Src\Merchant\App\Http\Resources\ProfileResource;
use Symfony\Component\HttpFoundation\Response;

class ProfileCtrl extends DomainBaseCtrl
{
    public function index(): JsonResponse
    {
        try {
            $profile = Profile::query()->where('customer_id', auth()->user()->id)
                ->with(['customer', 'customer.profile', 'customer.storeFront'])
                ->firstOrFail();

            return jsonResponse(Response::HTTP_OK,
                new CustomerResource($profile->customer)
            );
        } catch (Exception $e) {
            return \jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Profile has not been set',
            ]);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'firstname' => ['nullable', 'string'],
                'lastname' => ['nullable', 'string'],
                'phone_number' => ['nullable', 'string'],
                'email' => ['nullable', 'unique:App\Models\Customer,email'],
                'firebase_token' => ['nullable', 'string'],
                'business_name' => ['nullable', 'string'],
                'business_physical_address' => ['nullable', 'string'],
                'business_zip_code' => ['nullable', 'string'],
                'business_logo' => ['nullable', 'string'],
                'image' => ['nullable', 'string'],
                'can_receive_notification' => ['nullable', 'boolean', 'in:0,1'],
                'can_receive_subscription_reminder' => ['nullable', 'boolean', 'in:0,1'],
            ]);

            $customer = Customer::query()
                ->findOrFail($request->user()->id)
                ->fill($request->only(['email', 'phone_number', 'firebase_token', 'image',
                    'can_receive_notification', 'can_receive_subscription_reminder',
                ]));

            $isModifiedEmail = ($request->email !== auth()->user()->email) && $customer->isDirty('email');

            $customer->email_verified_at = ($isModifiedEmail) ? null : $customer->email_verified_at;

            $customer->save();

            $profile = Profile::query()
                ->where('id', auth()->user()->profile->id)
                ->with(['customer', 'customer.account', 'customer.storeFront'])
                ->firstOrFail();

            $profile
                ->fill($request->only([
                    'firstname', 'lastname', 'business_name', 'business_physical_address',
                    'business_logo', 'business_zip_code',
                ]))
                ->save();

            return jsonResponse(Response::HTTP_OK, new ProfileResource($profile));
        } catch (Exception $e) {
            logExceptionErrorMessage('ProfileCtrl', $e);

            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update profile',
            ]);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request): JsonResponse
    {
        auth()->user()->delete();

        return jsonResponse(Response::HTTP_OK, [
            'message' => 'Account deletion successful!',
        ]);
    }
}
