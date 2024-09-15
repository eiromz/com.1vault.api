<?php

namespace Src\Accounting\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Src\Accounting\App\Enum\Messages;
use Src\Template\Application\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CreateStoreFrontInventoryRequest extends FormRequest
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
            'business' => ['required'],
            'image' => ['required', 'url'],
            'stock_status' => ['required', 'boolean'],
            'description' => ['required'],
        ];
    }

    public function execute(): void
    {
        $this->merge([
            'is_published' => false,
            'product_name' => $this->name,
            'business_id' => $this->business,
            'is_store_front' => true,
            'quantity' => 0,
            'unit' => 'pcs',
            'selling_price' => $this->amount,
        ]);
        $this->validated();
    }

    /**
     * @throws BaseException
     */
    protected function failedAuthorization()
    {
        logExceptionErrorMessage('CreateStoreFrontInventory', null,
            ['type' => 'Authorization error from form request'], 'critical'
        );
        throw new BaseException(Messages::UNAUTHORIZED->value, ResponseAlias::HTTP_BAD_REQUEST);
    }
}
