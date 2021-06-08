<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPartnerResource extends JsonResource
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
            'id'               => $this->id,
            'contract_user_id' => $this->contract_user_id,
            'user_name'        => $this->user_name,
            'avatar'           => $this->avatar,
            'blocked_faq'      => $this->blocked_faq,
            'language'         => $this->language->shortcode,
            'this_referral'    => $this->this_referral,
            'referrals_count'  => $this->subscribers()->count(),
            'subscribers'      => UserSubscriberResource::collection($this->subscribers),
            'wallet'           => new WalletResouce($this->wallet),
            'platforms'        => $this->wallet->platforms()->get()->count(),
            'activated_platform' => $this->wallet->platforms()->where('activated', true)->get()->count(),
            'created_at'       => $this->created_at,
        ];
    }
}
