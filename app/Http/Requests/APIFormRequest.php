<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\RequestAllTrait;
use App\Traits\FormatsErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

/**
 * Class APIFromRequest
 *
 * @package App\Http\Requests
 */
abstract class APIFormRequest extends FormRequest
{
    use FormatsErrorResponse;
    use RequestAllTrait;

    /**
     * @var bool
     */
    private bool $oneMessage = false;

    /**
     * Set variable for getting one message in response
     */
    protected function returnOneMessage(): void
    {
        $this->oneMessage = true;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize(): bool;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Handle errors in validation
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function failedValidation(Validator $validator): void
    {
        $errors = null;

        if ($this->oneMessage) {
            $errors = $validator->errors()->first();
        } else {
            $errors = $validator->errors();
        }

        throw new HttpResponseException(response($this->errorResponse($errors), Response::HTTP_BAD_REQUEST));
    }
}
