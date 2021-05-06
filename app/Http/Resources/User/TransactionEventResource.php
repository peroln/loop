<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "referrer_id" => $this->referrer_id,
            "contract_user_id" => $this->contract_user_id,
            "referrer_base58_address" => $this->referrer_base58_address,
            "contract_user_base58_address" => $this->contract_user_base58_address,
            'block_number' => $this->block_number,
            'block_timestamp' => $this->block_timestamp,
            'event_name' => $this->event_name,
        ];
    }
}
