<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Rules\Recaptcha;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'email'                => ['required', 'string', 'email', 'max:255', 'regex:' . Admin::EMAIL_REGEX],
            'password'             => ['required', 'string', 'min:8'],
            'remember'             => ['boolean'],
            'g_recaptcha_response' => ['required', new Recaptcha()],
        ];
    }
}
