<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait Base64Calculations
 *
 * @package App\Traits
 */
trait Base64Calculations
{
    /**
     * Calculate base64 file size
     *
     * @param string $value
     * @return int
     */
    protected function calculateBytesInStr(string &$value): int
    {
        $n = strlen($value); // size base64 string
        $y = 3;
        if (Str::endsWith($value, '==')) {
            $y = 2;
        } elseif (Str::endsWith($value, '=')) {
            $y = 1;
        }
        return (int) ($n * (3 / 4)) - $y; // Calculate file size in bytes
    }
}
