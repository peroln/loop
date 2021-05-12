<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
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
            'hex' => 'required|string|min:10|unique:transactions',
            'language' => ['nullable', 'string', 'exists:languages,shortcode'],
            'user_name' => 'nullable|string',
            'amount_transfers' => 'nullable|integer',
            'profit_referrals' => 'nullable|integer',
            'profit_reinvest' => 'nullable|integer',
        ];
    }
}
