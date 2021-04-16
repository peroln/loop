<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

/**
 * Trait CodeGenerator
 *
 * @package App\Traits
 */
trait CodeGenerator
{
    /**
     * @return array
     * @throws \Exception
     */
    protected function generateCode(): array
    {
        $code = (string)random_int(100000, 999999);
        $hash = Hash::make($code);
        return compact('code', 'hash');
    }

    /**
     * @param string $codeHash
     * @param string $dbHash
     * @return bool
     */
    protected function checkCode(string $codeHash, string $dbHash): bool
    {
        return Hash::check($codeHash, $dbHash);
    }
}
