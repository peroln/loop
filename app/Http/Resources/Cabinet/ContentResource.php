<?php

namespace App\Http\Resources\Cabinet;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
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
            'text'=> $this->text,
            'language_shortcode' => $this->language->shortcode
        ];
    }
}
