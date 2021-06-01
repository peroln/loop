<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\User\WalletResouce;
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
            ];
    }
}
