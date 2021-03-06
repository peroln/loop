<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'            => ['required', 'email', 'unique:users'],
            'password'         => [
                'required', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
            'user_name'        => 'string',
            'contract_address' => ['required', 'string', 'exists:wallets,address'],
        ];
    }
}
