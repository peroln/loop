<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

/**
 * Trait ValidatorExtends
 *
 * @package App\Traits
 */
trait ValidatorExtends
{
    /**
     * Registering new validation rules
     */
    public function registerValidatorNewRules(): void
    {
        $this->regRequiredXor();
        $this->regNumber();
    }

    /**
     * Added new validation
     * @href https://github.com/laravel/framework/issues/9724
     */
    protected function regRequiredXor(): void
    {
        Validator::extend(
            'required_xor',
            static function ($attribute, $value, $params, $validator): bool {
                $values = $validator->getData();

                $selfEmpty = empty($values[$attribute]);

                $containsNeither = $selfEmpty || !array_key_exists($params[0], $values);

                $containsBoth = array_intersect_key(array_flip($params), array_filter($values));

                return (empty($containsBoth) || $selfEmpty) && !$containsNeither; // no clashes and counterpart is filled
            },
            'You can only provide :attribute when the :xor_values are not provided'
        );

        Validator::replacer(
            'required_xor',
            static function ($message, $attribute, $rule, $params): string {
                $message = str_replace(':xor_values', implode(',', $params), $message);

                return $message;
            }
        );
    }

    protected function regNumber(): void
    {
        Validator::extendImplicit(
            'number',
            function ($attribute, $value, $parameters, $validator) {
                return (is_int($value) || is_float($value)) && !is_string($value);
            },
            'The :attribute must be number'
        );
        Validator::extendImplicit(
            'number_or_null',
            function ($attribute, $value, $parameters, $validator) {
                return (is_int($value) || is_float($value) || is_null($value)) && !is_string($value);
            },
            'The :attribute must be number or null'
        );
    }
}
