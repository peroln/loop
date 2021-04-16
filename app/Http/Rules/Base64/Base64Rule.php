<?php

declare(strict_types=1);

namespace App\Http\Rules\Base64;

use Illuminate\Contracts\Validation\Rule;

class Base64Rule implements Rule
{
    public const BASE64_REGEX = "/data:([a-zA-Z]*)\/([a-zA-Z]*);base64,([^\"]*)/";

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        return preg_match(self::BASE64_REGEX, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Bas64 format invalid';
    }
}
