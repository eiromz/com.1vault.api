<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class TransactionPinCtrl extends DomainBaseCtrl
{
    public function store(Request $request)
    {
        $request->validate([
            'current_pin' => ['required',Password::min(5)->numbers()],
            'pin' => ['required','confirmed',Password::min(5)->numbers()],
            'pin_confirmation' => ['required'],
        ]);

        if(!Hash::check($request->current_pin,auth()->user()->transaction_pin)){
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => "Failed to update your pin"
            ]);
        }

        $request->user()->fill([
            'transaction_pin' => Hash::make($request->pin)
        ]);

        $request->user()->save();

        return jsonResponse(Response::HTTP_OK, [
            'message' => "Successfully Updated Your pin"
        ]);
    }
}
