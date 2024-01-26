<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class TransactionPinCtrl extends DomainBaseCtrl
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'in:forgot,change,create'],
            'current_pin' => ['required_if:type,change', 'size:6', Password::min(5)->numbers()],
            'password' => ['required_if:type,forgot'],
            'pin' => ['required', 'confirmed', 'size:6', Password::min(5)->numbers()],
            'pin_confirmation' => ['required'],
        ]);

        if ($request->type === 'change' && ! Hash::check($request->current_pin, auth()->user()->transaction_pin)) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update your pin',
            ]);
        }

        if ($request->type === 'forgot' && ! Hash::check($request->password, auth()->user()->getAuthPassword())) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => 'Failed to update your pin',
            ]);
        }

        $customer = Customer::query()->find(auth()->user()->id);

        $customer->transaction_pin = Hash::make($request->pin);

        if (! $customer->save()) {
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => "Failed {$request->type} pin operation ",
            ]);
        }

        return jsonResponse(Response::HTTP_OK, [
            'message' => "Pin {$request->type} operation successful",
        ]);
    }
}
