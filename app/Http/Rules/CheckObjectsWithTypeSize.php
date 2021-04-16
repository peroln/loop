<?php

declare(strict_types=1);

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

/**
 * Class CheckObjectsWithTypeSize
 *
 * @package App\Rules
 */
class CheckObjectsWithTypeSize implements Rule
{
    /**
     * @var int
     */
    private int $minCount;

    /**
     * @var string
     */
    private string $typeName;

    /**
     * CheckObjectsWithTypeSize constructor.
     *
     * @param int    $minCount
     * @param string $typeName
     */
    public function __construct(int $minCount, string $typeName)
    {
        $this->minCount = $minCount;
        $this->typeName = $typeName;
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
        if (is_null($value)) {
            return false;
        }
        $filteredArr = Arr::where($value, fn($var) => $var['type'] === $this->typeName);

        return count($filteredArr) >= $this->minCount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return sprintf(
            'Count elements in array with type %s, must be equal or greater than %s',
            $this->typeName,
            $this->minCount
        );
    }
}
