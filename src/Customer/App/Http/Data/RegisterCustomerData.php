<?php

namespace Src\Customer\App\Http\Data;

use App\Models\Customer;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Src\Customer\App\Enum\AccountStatus;
use Src\Customer\App\Enum\Role;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterCustomerData extends Data
{
    #[Email]
    #[Unique('customers','email')]
    #[Required]
    public string $email;
    #[BooleanType]
    public bool $is_owner = true;
    #[BooleanType]
    public bool $is_member = false;
    #[IntegerType]
    public int $status = AccountStatus::PENDING->value;
    #[BooleanType]
    public bool $accept_terms_conditions = true;
    #[StringType]
    public string $role = Role::BUSINESS_OWNER->value;
    #[StringType]
    public ?string $referral_code;
    #[StringType]
    public ?string $otp;
    #[StringType]
    public ?string $ACCOUNTID;
    public ?object $customer;

    public function newCustomerInstance(): object|null
    {
        return $this->customer = (new Customer())->fill([
            'email'                 => $this->email,
            'role'                  => $this->role,
            'is_owner'              => $this->is_owner,
            'is_member'             => $this->is_member,
            'status'                => $this->status,
            'ACCOUNTID'             => generateAccountId(),
            'referral_code'         => generateReferralCode(),
            'otp'                   => generateOtpCode(),
            'otp_expires_at'        => now()->addMinutes(15),
            'accept_terms_conditions' => $this->accept_terms_conditions,
        ]);
    }
}
