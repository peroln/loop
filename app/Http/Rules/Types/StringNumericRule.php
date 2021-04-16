<?php

declare(strict_types=1);

namespace App\Http\Rules\Types;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class StringNumericRule
 *
 * @package App\Rules\Types
 */
class StringNumericRule implements Rule
{
    public const REGEX = '/^[-+]?\d*\.?\d*$/';

    /**
     *
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
        return (bool) preg_match(self::REGEX, (string) $value);
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
