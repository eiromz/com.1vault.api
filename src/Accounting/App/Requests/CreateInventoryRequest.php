<?php

namespace Src\Accounting\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInventoryRequest extends FormRequest
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
            'amount' => ['required'],
            'quantity' => ['required', 'integer'],
            'unit' => ['required'],
            'business' => ['required'],
            'selling_price' => ['nullable'],
            'image' => ['nullable', 'url'],
            'stock_status' => ['nullable', 'boolean'],
        ];
    }
}
