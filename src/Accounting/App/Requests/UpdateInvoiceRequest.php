<?php

namespace Src\Accounting\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'total'             => ['required'],
            'payment_status'    => ['required','integer','in:1,0'],
        ];
    }
}
