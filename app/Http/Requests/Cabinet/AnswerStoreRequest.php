<?php

namespace App\Http\Requests\Cabinet;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AnswerStoreRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'question_id' => 'required|integer|exists:questions,id',
            'text'    => 'required|string|unique:answers',
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
