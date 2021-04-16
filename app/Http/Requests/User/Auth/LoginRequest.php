<?php

namespace App\Http\Requests\User\Auth;

use App\Http\Rules\Recaptcha;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                => ['required', 'email', 'max:255'],
            'password'             => ['required', 'string', 'min:8', 'regex:' . User::PASSWORD_REGEX],
            'remember'             => ['boolean'],
            'deviceId'             => ['nullable', 'string', 'min:8'],
            'g_recaptcha_response' => ['required', new Recaptcha()],
        ];
    }
}
