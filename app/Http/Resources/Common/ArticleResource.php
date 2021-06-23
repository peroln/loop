<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Cabinet\ContentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'id'         => $this->id,
            'author'     => $this->user->user_name,
            'contents'   => ContentResource::collection($this->contents),
            'created_at' => $this->created_at,
        ];
    }
}
