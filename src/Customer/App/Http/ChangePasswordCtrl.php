<?php

namespace Src\Customer\App\Http;

use App\Http\Controllers\DomainBaseCtrl;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordCtrl extends DomainBaseCtrl
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'current_password' => ['required',Password::min(8)->letters()->mixedCase()->uncompromised()],
            'password' => ['required','confirmed',Password::min(8)->letters()->mixedCase()->uncompromised()],
            'password_confirmation' => ['required'],
        ]);

        if(!Hash::check($request->current_password,auth()->user()->getAuthPassword())){
            return jsonResponse(Response::HTTP_BAD_REQUEST, [
                'message' => "Failed to update your password"
            ]);
        }

        $request->user()->fill([
            'password' => Hash::make($request->password)
        ]);

        $request->user()->save();

        return jsonResponse(Response::HTTP_OK, [
            'message' => "Successfully Updated Your Password"
        ]);
    }
}
