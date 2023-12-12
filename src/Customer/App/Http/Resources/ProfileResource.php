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
            'id'            => $this->id,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'business_name' => $this->business_name ?? 'N/A',
            'account_number' => $this->account_number ?? 'N/A',
            'state_id'      => (new StateResource($this->whenLoaded('state'))),
            'customer_id'      => (new CustomerResource($this->whenLoaded('customer'))),
        ];
    }
}
