<?php

namespace App\Http\Requests\Cabinet;

use App\Models\Cabinet\Question;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content'                      => 'required_without_all:active,approved|array',
            'content.*.text'               => [
                'required_with:content.*.language_shortcode', 'string', Rule::unique('contents')->where(function ($query) {
                    return $query->whereNotIn('contentable_id', [$this->question->id])
                        ->where('contentable_type', Question::class);
                }),
            ],
            'content.*.language_shortcode' => 'required_with:content.*.text|string|exists:languages,shortcode',
            'active'                       => ['integer', Rule::in([0, 1])],
            'approved'                     => ['integer', Rule::in([0, 1])],
        ];
    }
}
