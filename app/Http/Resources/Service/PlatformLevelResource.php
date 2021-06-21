<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PlatformLevelResource extends JsonResource
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
            'id'                         => $this->id,
            'name'                       => $this->name,
            'cost_buy'                   => $this->cost_buy,
            'cost_activation'            => $this->cost_activation,
            'cost_gaz'                   => $this->cost_gaz,
            'count_platform_subscribers' => $this->count_platform_subscribers,
            'league' => $this->league->name,
            'last_filled_platform' => $this->when(Auth::guard('admins')->check(), function(){
                return $this->getLastFilledPlatform();
            })

        ];
    }
}
