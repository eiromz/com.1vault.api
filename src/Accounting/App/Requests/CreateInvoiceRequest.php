<?php

namespace Src\Accounting\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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
            'invoice_date'      => ['required', 'date', 'after_or_equal:today'],
            'due_date'          => ['required', 'date', 'after_or_equal:today'],
            'items'             => ['required', 'array'],
            'note'              => ['nullable'],
            'amount_received'   => ['required'],
            'payment_method'    => ['required', 'in:pos,transfer,cash'],
            'discount'          => ['nullable'],
            'tax'               => ['required'],
            'shipping_fee'      => ['required'],
            'client'            => ['nullable', 'exists:App\Models\Client,id'],
            'business'          => ['nullable', 'exists:App\Models\Business,id'],
            'total'             => ['required']
        ];
    }
}
