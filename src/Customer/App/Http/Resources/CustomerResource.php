<?php

namespace Src\Customer\App\Http\Resources;

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
            'profile' => (new ProfileResource($this->whenLoaded('profile'))),
            'image' => $this->image
        ];
    }
}
