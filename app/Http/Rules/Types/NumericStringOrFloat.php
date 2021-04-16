<?php

declare(strict_types=1);

namespace App\Http\Rules\Types;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class NumericStringOrFloat
 *
 * @package App\Rules\Types
 */
class NumericStringOrFloat implements Rule
{
    /**
     *
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        return is_int($value) || is_float($value) || preg_match(StringNumericRule::REGEX, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Field value must have numeric format';
    }
}
