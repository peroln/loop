<?php

namespace App\Http\Requests\Admin;

use App\Rules\MatchOldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('admins')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password'     => ['required', new MatchOldPassword()],
            'new_password'         => [
                'required', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
            'new_confirm_password' => ['same:new_password'],
        ];
    }
}
