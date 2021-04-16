<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Trait GetClassName
 *
 * @package App\Traits
 */
trait GetClassName
{
    public static function className(): string
    {
        $PATH = explode('\\', get_called_class());

        return array_pop($PATH);
    }
}
