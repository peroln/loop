<?php

namespace App\Http\Resources\User\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'email'            => $this->email,
            'firstName'        => $this->first_name,
            'lastName'         => $this->last_name,
            'userName'         => $this->username,
            "lastLogin"        => $this->last_login,
            "lastActivity"     => $this->last_activity,
            //'authy2fa_enabled' => $this->authy2fa_enabled,
            'phone'            => $this->phone,
            'photo'            => $this->photo,
           // 'kyc_verification'  => $this->kyc->status ?? false,
        ];
    }
}
