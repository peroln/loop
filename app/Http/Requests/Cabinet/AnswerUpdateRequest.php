<?php

namespace App\Http\Requests\Cabinet;

use App\Models\Cabinet\Question;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnswerUpdateRequest extends FormRequest
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
            'user_id'     => 'required_without_all:question_id,text|integer|exists:users,id',
            'question_id' => 'required_without_all:user_id,text|integer|exists:questions,id',
            'content'                      => 'required_without_all:active,approved,user_id|array',
            'content.*.text'               => ['required_with:content.*.language_shortcode', 'string'],
            'content.*.language_shortcode' => 'required_with:content.*.text|string|exists:languages,shortcode',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('user_id')) {
            $this->merge([
                'user_id' => User::where('contract_user_id', $this->user_id)->first()?->id,
            ]);
        }
    }
}
