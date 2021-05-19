<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class RequestCommandRequest extends FormRequest
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
            'ref' => 'required|string|exists:commands,reference'
        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
           'ref' => $this->route('ref')
        ]);
    }
}
