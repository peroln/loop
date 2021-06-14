<?php

namespace App\Http\Requests\Cabinet;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class QuestionStoreRequest extends FormRequest
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
        Log::info('hello');
        return [
//            'user_id' => 'required|integer|exists:users,id',
            'content' => 'required|array',
            'content.*.text'    => ['required','string', 'unique:contents,text'],
            'content.*.language_shortcode' => 'required|string|exists:languages,shortcode'
        ];
    }

/*    public function prepareForValidation()
    {
        if($this->has('user_id')){
            $this->merge([
                'user_id' => User::where('contract_user_id', $this->user_id)->first()?->id,
            ]);
        }

    }*/
}
