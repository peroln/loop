<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;

class ReactivationResource extends JsonResource
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
            'platform_level_id' => $this->platform_level_id,
            'platform_level_name' => $this->platformLevel->name,
            'wallet_id' => $this->wallet_id,
            'count' => $this->count
        ];
    }
}
