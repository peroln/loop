<?php

namespace App\Traits;

use ReflectionClass;

/**
 * Trait LoadClassConstants
 *
 * @package App\Traits
 */
trait LoadClassConstants
{
    /**
     * @return array
     */
    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);

        return $oClass->getConstants();
    }

    /**
     * @return array
     */
    public static function getConstantsVal(): array
    {
        return array_values(self::getConstants());
    }
}
