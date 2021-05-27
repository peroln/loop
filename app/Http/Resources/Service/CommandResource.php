<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class CommandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'wallet_id' => $this->wallet_id,
            'wallets' => $this->wallets,
            'contract_user_id' => $this->wallet->user->contract_user_id
            ];
    }
}
