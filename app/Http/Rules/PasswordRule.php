<?php

declare(strict_types=1);

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class PasswordRule
 *
 * @package App\Rules
 */
class PasswordRule implements Rule
{
    private string $requiredPass;

    /**
     * Create a new rule instance.
     *
     * @param string $requiredPass
     */
    public function __construct(string $requiredPass)
    {
        $this->requiredPass = $requiredPass;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     * @throws \Exception
     */
    public function passes($attribute, $value): bool
    {
        return (\Hash::check($value, $this->requiredPass));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The current password is incorrect';
    }
}
