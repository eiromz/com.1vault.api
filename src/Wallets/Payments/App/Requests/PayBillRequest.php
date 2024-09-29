<?php

namespace Src\Wallets\Payments\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayBillRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|gte:100',
            'transaction_pin' => 'required',
            'bill_params' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $bill_params = $this->bill_params;

        $bill_params['transaction_ref'] = generateTransactionReference();

        $this->merge([
            'bill_params' => $bill_params,
        ]);
    }

    private function modifyTransactionReference($params): string {
        $params['transaction_ref'] = generateTransactionReference();
    }

    private function addCustomerAccountNumber($params): string {
        $params[''] =
    }
}
