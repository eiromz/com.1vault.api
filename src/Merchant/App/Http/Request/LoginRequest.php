<?php

namespace Src\Merchant\App\Http\Request;

use App\Models\Customer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Src\Merchant\App\Enum\Role;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate() : string
    {
        $this->validated();

        if (! Auth::attempt($this->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $name = createNameForToken($this->email);

        $abilities = match(auth()->user()->role) {
            Role::MERCHANT->value => Customer::OWNER_ABILITIES,
            Role::EMPLOYEE->value => Customer::EMPLOYEE_ABILITIES,
        };

        return auth()->user()
            ->createToken($name, $abilities)->plainTextToken;
    }
}
