<?php

namespace App\Http\Resources\Cabinet;

use App\Models\Cabinet\Question;
use App\Models\Common\Article;
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
            'subject'=> $this->when($this->contentable_type === Question::class, $this->subject),
            'title'=> $this->when($this->contentable_type === Article::class, $this->subject),
            'language_shortcode' => $this->language->shortcode
        ];
    }
}
