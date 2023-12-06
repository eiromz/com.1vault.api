<?php

namespace Src\Customer\App\Http\Data;

use App\Models\Customer;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Src\Customer\App\Enum\AccountStatus;
use Src\Customer\App\Enum\Role;

class VerifyEmailData extends Data
{
    #[Email]
    #[Exists]
    public string $email;

    #[StringType]
    #[Exists]
    public string $otp;

    public function emailAndOtpIsValid()
    {
        return Customer::where('email',$this->email)->firstOrFail();
    }

    public function populate()
    {
        return $this->customer->fill([
            'email'     => $this->email,
            'otp' => $this->otp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);
    }
}
