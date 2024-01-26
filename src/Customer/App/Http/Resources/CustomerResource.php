<?php

namespace Src\Customer\App\Http\Resources;

use App\Models\KnowYourCustomer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
            'accept_terms_conditions' => $this->accept_terms_conditions,
            'status' => $this->status,
            'ACCOUNTID' => $this->ACCOUNTID,
            'transaction_pin' => $this->transaction_pin,
            'referral_code' => $this->referral_code,
            'is_owner' => $this->is_owner,
            'is_member' => $this->is_member,
            'image' => $this->image,
            'profile' => (new ProfileResource($this->whenLoaded('profile'))),
            'pin-set' => $this->pinSet(),
            'kyc-complete' => $this->kycComplete(),
            'can_receive_notification' => $this->can_receive_notification,
            'can_receive_subscription_reminder' => $this->can_receive_subscription_reminder,
        ];
    }

    public function pinSet(): bool
    {
        $boolean = false;
        if (! is_null($this->transaction_pin)) {
            $boolean = true;
        }

        return $boolean;
    }

    public function kycComplete(): bool
    {
        $kyc = KnowYourCustomer::query()
            ->where('customer_id', '=', $this->id)
            ->where('status', '=', 1)
            ->first();
        $boolean = false;

        if (! is_null($this->transaction_pin)) {
            $boolean = true;
        }

        return $boolean;
    }
}
