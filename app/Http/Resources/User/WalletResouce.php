<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResouce extends JsonResource
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
            'user_id' => $this->user_id,
            'coin' => $this->coin,
            'address' => $this->address,
            'balance' => $this->balance,
            'referral_link' => $this->referral_link,
            'amount_transfers' => $this->amount_transfers,
            'profit_referrals' => $this->profit_referrals,
            'profit_reinvest' => $this->profit_reinvest,
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions'))
        ];
    }
}
