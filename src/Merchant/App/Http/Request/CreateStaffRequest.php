<?php

namespace Src\Merchant\App\Http\Request;

use App\Models\Profile;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Src\Merchant\App\Enum\Role;
use App\Models\Customer as Staff;

class CreateStaffRequest extends FormRequest
{
    public $staff;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $this->setDefault();
        return [
            'email'     => ['required','unique:App\Models\Customer,email','email'],
            'firstname' => ['required'],
            'lastname'  => ['required'],
            'password'  => ['required','confirmed',Password::min(8)->mixedCase()->numbers()->uncompromised()->symbols()],
            'password_confirmation' => ['required']
        ];
    }

    private function setDefault(): void
    {
        $now = now();
        $this->merge([
            'ACCOUNTID' => auth()->user()->ACCOUNTID,
            'referral_code' => generateReferralCode(),
            'status' => 1,
            'otp' => generateOtpCode(),
            'otp_expires_at' => $now,
            'email_verified_at' => $now,
            'accept_terms_conditions' => true,
            'is_owner' => false,
            'is_member' => true,
            'role' => Role::EMPLOYEE->value,
            'image' => "https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/avatar.jpg"
        ]);
    }

    private function createStaff()
    {
        $this->staff = Staff::query()->create($this->only([
            'email','email_verified_at','role','accept_terms_conditions','otp','otp_expires_at',
            'is_owner','is_member','status','ACCOUNTID','referral_code','image'
        ]));

        if($this->staff) $this->merge(
            ['customer_id' => $this->staff->id]
        );
    }

    private function createStaffProfile()
    {
        Profile::query()->create($this->only([
            'customer_id','firstname','lastname'
        ]));
    }
}
