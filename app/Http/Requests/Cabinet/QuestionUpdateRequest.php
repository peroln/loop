<?php

namespace App\Http\Requests\Cabinet;

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
            'user_id' => 'required_without_all:active,approved,text|integer|exists:users,id',
            'text'    => 'required_without_all:active,approved,user_id|string|unique:questions,text,' . $this->question->id,
            'active' => ['integer', Rule::in([0,1])],
            'approved' => ['integer', Rule::in([0,1])],
        ];
    }

    public function prepareForValidation()
    {
        if($this->has('user_id')){
            $this->merge([
                'user_id' => User::where('contract_user_id', $this->user_id)->first()?->id,
            ]);
        }

    }
}
