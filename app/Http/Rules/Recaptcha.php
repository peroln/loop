<?php

declare(strict_types=1);

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    use \App\Traits\ReCaptcha;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if ($value === "000000" && config('app.debug') === true) {
            return true;
        }

        return $this->validateCaptcha($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Recaptcha verification failed';
    }
}
