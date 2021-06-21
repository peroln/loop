<?php


namespace App\Http\Requests\Admin;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class IndexAdminUsersRequest extends FormRequest
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

    public function rules()
    {
        return [
            'page'      => 'sometimes|integer',
            'per_page'  => 'sometimes|integer',
            'searchByContractId' => 'integer|exists:users,contract_user_id'
        ];
    }
}
