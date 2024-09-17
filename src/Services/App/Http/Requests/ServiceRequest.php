<?php

namespace Src\Services\App\Http\Requests;

use App\Models\BusinessRequest;
use App\Models\LegalRequest;
use App\Models\PosRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public array $businessName = [
        'type', 'business_name', 'nature_of_business', 'government_id_pdf', 'email_address',
        'phone_number', 'physical_address', 'email_address_proprietors', 'phone_number_proprietors',
        'signature_of_proprietors_pdf', 'utility_bill_pdf', 'customer_id',
    ];

    public array $businessLlc = [
        'type', 'business_name', 'nature_of_business', 'government_id_pdf', 'email', 'email_address',
        'phone_number', 'physical_address', 'physical_address_of_directors',
        'email_address_directors', 'phone_number_directors', 'name_of_directors',
        'signature_of_proprietors_pdf', 'passport_photograph_of_directors_pdf', 'customer_id',
    ];

    public array $pos = [
        'business_name',
        'customer_id',
        'merchant_trade_name',
        'business_type',
        'category',
        'office_address',
        'local_govt_area',
        'state_id',
        'primary_contact_person',
        'secondary_contact_person',
        'pos_quantity',
        'pos_locations',
        'receive_notification',
        'notification_email_address',
        'notification_phone_number',
        'real_time_transaction_viewing',
        'settlement_account_name',
        'settlement_account_number',
        'settlement_branch',
        'other_information',
        'attestation',
        'card_type',
        'signature_pdf_link',
        'designation',
    ];

    public array $legal = [
        'customer_id',
        'description',
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->type) {
            'business_name','business_llc' => $this->registerBusinessRequestRules(),
            'pos' => $this->registerPosRequestRules(),
            'legal' => $this->legalRequestRules()
        };
    }

    public function registerBusinessRequestRules(): array
    {
        return [
            'type' => ['required',
                'in:business_name,business_llc,pos,legal',
            ],
            'business_name' => ['required', 'array'],
            'nature_of_business' => ['required'],
            'government_id_pdf' => ['required', 'url'],
            'email_address' => ['required', 'email'],
            'phone_number' => ['required'],
            'physical_address' => ['required'],
            'email_address_proprietors' => ['required_if:action,business_name'],
            'email_address_directors' => ['required_if:action,business_llc'],
            'phone_number_proprietors' => ['required_if:action,business_name'],
            'phone_number_directors' => ['required_if:action,business_llc'],
            'physical_address_of_directors' => ['required_if:action,business_llc'],
            'name_of_directors' => ['required_if:action,business_llc'],
            'signature_of_proprietors_pdf' => ['required_if:action,business_name', 'url'],
            'signature_of_directors_pdf' => ['required_if:action,business_llc'],
            'passport_photograph_of_directors_pdf' => ['required_if:action,business_llc'],
            'utility_bill_pdf' => ['required_if:action,business_name', 'url'],
            'comments' => ['nullable'],
        ];
    }

    public function registerPosRequestRules(): array
    {
        $new = new PosRequest;

        return [
            'type' => ['required', 'in:business_name,business_llc,pos,legal'],
            'business_name' => ['required'],
            'merchant_trade_name' => ['required'],
            'business_type' => ['required'],
            'category' => ['required'],
            'office_address' => ['required'],
            'local_govt_area' => ['required'],
            'state_id' => ['required'],
            'primary_contact_person' => ['required', 'array'],
            'secondary_contact_person' => ['required', 'array'],
            'pos_quantity' => ['required', 'integer'],
            'pos_locations' => ['required', 'array'],
            'receive_notification' => ['required', 'boolean', 'in:1,0'],
            'notification_email_address' => ['nullable', 'email'],
            'notification_phone_number' => ['nullable'],
            'real_time_transaction_viewing' => ['nullable', 'boolean'],
            'settlement_account_name' => ['required', 'string'],
            'settlement_account_number' => ['required', 'string'],
            'settlement_branch' => ['required', 'string'],
            'other_information' => ['nullable', 'string'],
            'attestation' => ['required', 'string'],
            'card_type' => ['nullable', 'string'],
            'signature_pdf_link' => ['required', 'url'],
            'designation' => ['nullable'],
        ];
    }

    public function legalRequestRules(): array
    {
        return [
            'type' => ['required', 'in:business_name,business_llc,pos,legal'],
            'description' => ['required', 'max:100'],
        ];
    }

    public function getOnly(): array
    {
        return match ($this->type) {
            'business_name' => $this->only($this->businessName),
            'business_llc' => $this->only($this->businessLlc),
            'pos' => $this->only($this->pos),
            'legal' => $this->only($this->legal)
        };
    }

    public function getModel(): Builder
    {
        return match ($this->type) {
            'business_name','business_llc' => BusinessRequest::query(),
            'pos' => PosRequest::query(),
            'legal' => LegalRequest::query()
        };
    }
}
