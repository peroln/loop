<?php

declare(strict_types=1);

namespace App\Http\Rules\Base64;

use App\Traits\Base64Calculations;
use Illuminate\Contracts\Validation\Rule;

/**
 * Class Base64SizeRule
 *
 * @package App\Rules
 */
class Base64SizeRule implements Rule
{
    use Base64Calculations;

    private int $maxBytes;

    private int $currentFileSize = 0;

    /**
     * Base64SizeRule constructor.
     *
     * @param int $maxBytes
     */
    public function __construct(int $maxBytes)
    {
        $this->maxBytes = $maxBytes;
    }

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
        $this->currentFileSize = $this->calculateBytesInStr($value);

        return $this->currentFileSize <= $this->maxBytes;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return sprintf(
            "Length file must be less than %s MB, current size: %s MB",
            bytesToMegabyte($this->maxBytes),
            bytesToMegabyte($this->currentFileSize)
        );
    }
}
