<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\APIFormRequest;
use Auth;

class ForgotPasswordRequest extends APIFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:admins'],
        ];
    }
}
