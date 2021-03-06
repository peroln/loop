<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'email'     => $this->email,
            'firstName' => $this->first_name,
            'lastName'  => $this->last_name,
            'userName'  => $this->username,
            "lastLogin" => $this->last_login,
        ];
    }
}
