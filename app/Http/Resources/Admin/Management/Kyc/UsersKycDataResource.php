<?php

namespace App\Http\Resources\Admin\Management\Kyc;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersKycDataResource extends JsonResource
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
            'id'             => $this->id,
            'status'         => $this->kyc->status ?? null,
            'firstname'      => $this->first_name,
            'lastname'       => $this->last_name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'kyc'            => $this->kyc()->get(),
            'kyc_documents'  => $this->kyc->documents ?? [],
        ];
    }
}