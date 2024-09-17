<?php

namespace Src\Merchant\App\Http\Data;

use App\Models\Customer;
use App\Models\Profile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Password;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Src\Template\Application\Exceptions\BaseException;
use Symfony\Component\HttpFoundation\Response;

class CompleteCustomerProfileData extends Data
{
    #[Required]
    #[Min(3)]
    public string $first_name;

    #[Required]
    #[Min(3)]
    public string $last_name;

    #[Required]
    #[Unique('customers', 'phone_number')]
    #[Min(11)]
    public string $phone_number;

    #[Password(min: 8, mixedCase: true, numbers: true, symbols: true, uncompromised: true, uncompromisedThreshold: 0)]
    #[Confirmed]
    public string $password;

    #[Nullable]
    #[Min(3)]
    public ?string $business_name = 'N/A';

    #[Required]
    #[Exists('states', 'id')]
    public int $state_id;

    /**
     * @throws Exception
     */
    public function execute($customer): void
    {
        try {
            $this->updatePassword($customer);

            DB::beginTransaction();

            Profile::query()->createOrFirst([
                'customer_id' => $customer->id,
                'firstname' => $this->first_name,
                'lastname' => $this->last_name,
                'business_name' => $this->business_name,
                'country_id' => 160,
                'state_id' => $this->state_id,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new BaseException('Failed to update customer profile', Response::HTTP_BAD_REQUEST);
        }
    }

    public function updatePassword($customer): void
    {
        $customer = Customer::query()->find($customer->id);
        $customer->password = Hash::make($this->password);
        $customer->phone_number = $this->phone_number;
        $customer->save();
    }
}
