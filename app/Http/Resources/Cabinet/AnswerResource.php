<?php

namespace App\Http\Resources\Cabinet;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'id'               => $this->id,
            'user_contract_id' => $this->user->contract_user_id,
            'question_id'      => $this->question_id,
            'contents'         => ContentResource::collection($this->contents),
            'active'           => $this->active,
        ];
    }
}
