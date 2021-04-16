<?php

namespace App\Http\Resources\Admin\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdminDataResource
 * @package App\Http\Resources\Admin\Auth
 * @mixin \App\Models\Admin
 */
class AdminDataResource extends JsonResource
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
            "phone"            => $this->phone ?? null,
            "authy2fa_enabled" => $this->authy2fa_enabled,
            "super_admin"      => $this->isSuperAdmin(),
            'permissions'      => $this->permissions ?? null,
        ];
    }
}
