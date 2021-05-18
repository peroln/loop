<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class GetUserByContractIdRequest extends FormRequest
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
            'contract_user_id' => 'required|integer|exists:users'
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            'contract_user_id' => $this->route('contract_user_id')
        ]);
    }
}
