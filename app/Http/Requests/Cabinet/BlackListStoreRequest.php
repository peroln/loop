<?php

namespace App\Http\Requests\Cabinet;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class BlackListStoreRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id|unique:black_lists'
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
    public function messages()
    {
        return [
            'user_id.required' => 'The ID of user contract  does not exist or valid',
        ];
    }
}
