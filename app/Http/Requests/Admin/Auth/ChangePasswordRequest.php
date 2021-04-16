<?php

namespace App\Http\Requests\Admin\Auth;

use App\Http\Requests\APIFormRequest;
use App\Http\Rules\PasswordRule;
use App\Models\Admin;
use App\Services\Base\BaseAppGuards;
use Auth;

class ChangePasswordRequest extends APIFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $admin = auth(BaseAppGuards::ADMIN)->user();

        return [
            'current_password' => [
                'required',
                new PasswordRule($admin->password),
            ],
            'password' => ['required', 'regex:' . Admin::PASSWORD_REGEX, 'confirmed'],
        ];
    }
}
