<?php

namespace Src\Wallets\Payments\App\Requests;

use App\Exceptions\BaseException;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CreateJournalRequest extends FormRequest
{
    public $profile;

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
            'account_number' => ['required', 'exists:App\Models\Profile,account_number'],
            'amount' => ['required'],
            'transaction_pin' => ['required'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $this->merge([
            'trx_ref' => generateTransactionReference(),
            'label' => 'wallet to wallet transfer',
            'source' => 'wallet transaction',
        ]);

        $this->verifyTransactionPin();
        $this->validated();
        $this->getAccountNumberInformation();
    }

    public function getAccountNumberInformation(): void
    {
        $this->profile = Profile::query()
            ->where('account_number', '=', $this->account_number)
            ->with('customer')
            ->first();
    }

    /**
     * @throws BaseException
     */
    public function verifyTransactionPin()
    {
        if (Hash::check($this->transaction_pin, auth()->user()->transaction_pin)) {
            return;
        }

        throw new BaseException('Invalid Transaction pin', Response::HTTP_BAD_REQUEST);
    }
}
