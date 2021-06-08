<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSubscriberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'contract_user_id'    => $this->contract_user_id,
            'referrals_count'     => $this->subscribers()->count(),
            'wallet'              => $this->wallet()->select('id', 'address', 'amount_transfers')->get(),
            'platforms'           => $this->wallet->platforms()->get()->count(),
            'activated_platforms' => $this->wallet->platforms()->where('activated', true)->get()->count(),
            'created_at'          => $this->created_at,
        ];
    }
}
