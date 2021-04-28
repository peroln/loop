<?php

namespace App\Http\Resources\User;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Request;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'user_name'        => $this->user_name,
            'avatar'           => $this->avatar,
            'blocked_faq'      => $this->blocked_faq,
            'lang'             => $this->lang,
            'this_referral'    => $this->this_referral,
            'referrals_count'  => $this->getCountReferrals($this->id),
            'referral_link'    => $this->getReferralLink($this->id),
            'wallet'           => new WalletResouce($this->wallet),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,

        ];
    }

    public function getCountReferrals(int $id): int
    {
       return User::where('this_referral', $id)->count();
    }

    public function getReferralLink(int $id): string
    {
        return config('app.domain') . '/t/' . $id;
    }

}
