<?php

declare(strict_types=1);

namespace App\Http\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfExistOnIdRule implements Rule
{
    /**
     * @var string
     */
    private string $tableName;

    /**
     * CheckIfExistOnIdRule constructor.
     *
     * @param string $tableName
     */
    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
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
        return $value === '*' || \DB::table($this->tableName)->where('id', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return sprintf("Current item does not exist in %s table", str_replace('_', ' ', $this->tableName));
    }
}
