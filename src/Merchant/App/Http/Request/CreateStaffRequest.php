<?php

namespace Src\Merchant\App\Http\Request;

use App\Mail\NewStaffCreationMail;
use App\Models\Customer as Staff;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Src\Merchant\App\Enum\Role;

class CreateStaffRequest extends FormRequest
{
    public $staff;

    public function authorize(): bool
    {
        return auth()->user()->role === Role::MERCHANT->value;
    }

    public function rules(): array
    {
        $this->setDefault();

        return [
            'email' => ['required', 'unique:App\Models\Customer,email', 'email'],
            'firstname' => ['required'],
            'lastname' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->uncompromised()->symbols()],
            'password_confirmation' => ['required'],
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
            'country_id' => auth()->user()->profile->country_id,
            'state_id' => auth()->user()->profile->state_id,
            'image' => 'https://1vault-staging-1.fra1.cdn.digitaloceanspaces.com/1vault-staging-1/docs/avatar.jpg',
        ]);
    }

    public function createStaff(): void
    {
        $this->staff = Staff::query()->create($this->only([
            'email', 'email_verified_at', 'role', 'accept_terms_conditions', 'otp', 'otp_expires_at',
            'is_owner', 'is_member', 'status', 'ACCOUNTID', 'referral_code', 'image',
        ]));

        if ($this->staff) {
            $this->merge(
                ['customer_id' => $this->staff->id]
            );
        }
    }

    public function createStaffProfile(): void
    {
        Profile::query()->create($this->only([
            'customer_id', 'firstname', 'lastname', 'country_id', 'state_id',
        ]));

        $this->staff->load('profile');
    }

    public function sendWelcomeEmail(): void
    {
        try {
            $profile = auth()->user()->profile ?? null;

            if ($profile) {
                Mail::to($this->email)->queue(new NewStaffCreationMail($profile->fullname, auth()->user()->email));
            }
        } catch (\Exception $e) {
            logExceptionErrorMessage('sendWelcomeEmail', $e);
        }
    }
}
