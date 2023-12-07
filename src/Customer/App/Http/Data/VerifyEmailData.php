<?php

namespace Src\Customer\App\Http\Data;

use App\Models\Customer;
use Carbon\Carbon;
use Exception;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Src\Customer\App\Enum\AccountStatus;
use Src\Customer\App\Enum\Role;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;

class VerifyEmailData extends Data
{
    #[Email]
    #[Required]
    #[Exists('customers','email')]
    public string $email;

    #[Required]
    #[Exists('customers','otp')]
    public int $otp;

    #[Nullable]
    public ?object $customer;

    /**
     * @throws Exception
     */
    public function execute()
    {
        $this->customer = Customer::where('email',$this->email)
            ->where('otp',$this->otp)->firstOrFail();

        if($this->customer->otp_expires_at->isPast()){
            throw new Exception('Otp has expired!',Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->customer->email_verified_at = now();
        $this->customer->status = AccountStatus::ACTIVE->value;

        return $this->customer->save();
    }
}
