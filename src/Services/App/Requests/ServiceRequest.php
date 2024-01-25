<?php

namespace Src\Services\App\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
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
        return match($this->action) {
            'business_name','business_llc' => $this->registerBusinessRequestRules(),
            'register_pos_request' => $this->registerPosRequestRules(),
        };
    }

    //register_business_request
    //register_business_llc_request
    //pos_request
    public function registerBusinessRequestRules(): array
    {
        return [
            'action' => ['required',
                'in:business_name,business_llc,pos_request'
            ],
            'title'                         => ['required','array'],
            'nature_of_business'            => ['required',],
            'government_id_pdf'             => ['required','url'],
            'email_address'                 => ['required','email'],
            'phone_number'                  => ['required'],
            'physical_address'              => ['required'],
            'email_address_proprietors'     => ['required'],
            'email_address_directors'       => ['required_if:action:business_llc'],
            'phone_number_proprietors'      => ['required'],
            'phone_number_directors'        => ['required_if:action:business_llc'],
            'physical_address_of_directors' => ['required_if:action:business_llc'],
            'name_of_directors'             => ['required_if:action:business_llc'],
            'signature_of_proprietors_pdf'  => ['required','url'],
            'signature_of_proprietors_directors_pdf'  => ['required_if:action:business_llc'],
            'passport_photograph_of_directors_pdf'  => ['required_if:action:business_llc'],
            'utility_bill_pdf'              => ['required','url'],
            'comments'                      => ['nullable'],
        ];
    }

    public function registerPosRequestRules(): array
    {
        return [
            'action'                        => ['required', 'in:business_name,business_llc,pos_request'],
            'business_name'                 => ['required'],
            'merchant_trade_name'           => ['required'],
            'business_type'                 => ['required','in:sole_owner,partnership,ltd,plc,others'],
            'others'                        => ['required_if:business_type,others'],
            'category'                      => ['required'],
            'office_address'                => ['required'],
            'local_govt_area'               => ['required'],
            'state_id'                      => ['required'],
            'primary_contact_person'        => ['required','array'],
            'secondary_contact_person'      => ['required','array'],
            'pos_number'                     => ['required','integer'],
            'pos_location'                   => ['required','array'],
            'receive_notification'           => ['required','boolean'],
            'notification_email_address'     => ['required','email'],
            'notification_phone_number'      => ['required'],
            'real_time_transaction_viewing'  => ['required','boolean'],
            'settlement_account_name'        => ['required','string'],
            'settlement_account_number'      => ['required','string'],
            'settlement_branch'              => ['required','string'],
            'other_information'              => ['nullable','string'],
            'attestation'                    => ['required','string'],
            'card_type'                      => ['required','string','in:local_card,int_master_card,int_visa_card'],
            'signature_pdf_link'             => ['required','url'],
            'designation'                    => ['required','url'],
            'date'                           => ['required']
        ];
    }
}
