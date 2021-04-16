<?php

declare(strict_types=1);

namespace App\Http\Rules\Types;

use Illuminate\Contracts\Validation\Rule;

class IntegerRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        return is_int($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Field must to be integer';
    }
}
