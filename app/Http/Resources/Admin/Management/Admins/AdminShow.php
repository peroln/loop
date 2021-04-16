<?php

namespace App\Http\Resources\Admin\Management\Admins;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AdminShow
 * @package App\Http\Resources\Admin\Management\Tokens
 * @mixin  \App\Models\Admin
 */
class AdminShow extends JsonResource
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
            'name'        => $this->id,
            'email'       => $this->email,
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'active'      => $this->active,
            'phone'       => $this->phone,
            'permissions' => $this->permissions ?? null,
        ];
    }
}
