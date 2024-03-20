<?php

namespace Src\Accounting\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBusinessInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2'],
            'phone_number' => ['required', 'min:11'],
            'email' => ['required', 'email', 'unique:App\Models\Business,email'],
            'address' => ['required', 'min:3'],
            'state_id' => ['required', 'exists:App\Models\State,id'],
            'zip_code' => ['required', 'string'],
            'image' => ['required', 'url'],
        ];
    }
}
