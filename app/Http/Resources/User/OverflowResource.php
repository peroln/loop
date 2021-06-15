<?php

namespace App\Http\Resources\User;

use App\Services\EventsHandlers\OverflowPlatformEvent;
use Illuminate\Http\Resources\Json\JsonResource;

class OverflowResource extends JsonResource
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
            'platform_level_name' => $this->platformLevel->name,
            'count_all_overflow' => $this->transactions()->count(),
            'count_fire_overflow' => $this->transactions()->wherePivotBetween('created_at', [now()->subWeek(), now()])->count(),
        ];
    }
}
