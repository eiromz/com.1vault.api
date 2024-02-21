<?php

namespace Src\Accounting\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceiptRequest extends FormRequest
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
            'transaction_date' => ['required', 'date', 'after_or_equal:today'],
            'items' => ['required', 'array'],
            'description' => ['nullable'],
            'amount_received' => ['required'],
            'payment_method' => ['required', 'in:pos,transfer,cash'],
            'discount' => ['nullable'],
            'tax' => ['required'],
            'total' => ['required'],
        ];
    }
}
