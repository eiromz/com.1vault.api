<?php

namespace Src\Wallets\Payments\App\Requests;

use App\Exceptions\BaseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class NipTransferRequest extends FormRequest
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
            'transaction_pin'           => ['bail','required'],
            'narration'                 => ['nullable'],
            'currencyCode'              => ['required'],
            'beneficiaryBank'           => ['required'],
            'beneficiaryBankName'       => ['required'],
            'transactionAmount'         => ['required'],
            'beneficiaryAccountName'    => ['required'],
            'beneficiaryAccountNumber'  => ['required'],
            'saveBeneficiary'           => ['required','boolean']
        ];
    }
    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->verifyTransactionPin();
        $this->merge([
            'userName'              => config('providus-bank.rest_api_username'),
            'password'              => config('providus-bank.rest_api_password'),
            'sourceAccountName'     => auth()->user()->profile->fullname ?? 'N/A',
            'transactionReference'  => generateProvidusTransactionRef(),
            'amount'                => $this->transactionAmount,
            'remark'                => $this->narration,
            'saveBeneficiary'       => $this->saveBeneficiary ?? 0,
            'trx_type'              => 'nip'
        ]);
        $this->validated();
    }
    /**
     * @throws BaseException
     */
    public function verifyTransactionPin(): void
    {
        if (Hash::check($this->transaction_pin, auth()->user()->transaction_pin)) {
            return;
        }

        throw new BaseException('Invalid Transaction pin', Response::HTTP_BAD_REQUEST);
    }

    private function handleNullRemark(): void
    {
        $fullname =  auth()->user()->profile->fullname;

        if(is_null($this->remark)){
            $this->remark = "Transfer from {$fullname}";
        }
    }
    public function messages()
    {
        return [
            'saveBeneficiary.required' => 'Save beneficiary is required for transaction',
        ];
    }
}
