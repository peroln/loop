<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class PlatformResource extends JsonResource
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
            'wallet_id' => $this->wallet_id,
            'platform_level_id' => $this->platform_level_id,
            'platform_level' => $this->platformLevel->name,
            'active' => $this->active,
            'subscribers' => $this->wallets()->pluck('wallet_id')
        ];
    }
}
