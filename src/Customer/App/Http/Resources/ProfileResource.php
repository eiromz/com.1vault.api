<?php

namespace Src\Customer\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'business_name' => $this->business_name ?? 'N/A',
            'business_logo' => $this->business_logo ?? 'N/A',
            'business_zip_code' => $this->business_zip_code ?? 'N/A',
            'business_physical_address' => $this->business_physical_address ?? 'N/A',
            'account_number' => $this->account_number ?? 'N/A',
            'state_id' => (new StateResource($this->whenLoaded('state'))),
            'customer_id' => (new CustomerResource($this->whenLoaded('customer'))),
            'account' => (new AccountResource($this->whenLoaded('account'))),
            'profile-complete' => $this->profileComplete(),
            'pin-set' => $this->pinSet(),
            'kyc-complete' => $this->kycComplete(),
        ];
    }

    /**
     * Profile has been complete
     */
    public function profileComplete(): bool
    {
        $return = false;

        if (! is_null(auth()->user()->profile)) {
            $return = true;
        }

        return $return;
    }

    /**
     * Pin has been set
     */
    public function pinSet(): bool
    {
        $return = false;

        if (! is_null(auth()->user()->transaction_pin)) {
            $return = true;
        }

        return $return;
    }

    /**
     * Kyc has been complete
     */
    public function kycComplete(): bool
    {
        $return = false;

        if (! is_null(auth()->user()->knowYourCustomer)) {
            $return = true;
        }

        return $return;
    }
}
