<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Admin;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Trait PasswordGenerator
 * @package App\Traits
 */
trait PasswordGenerator
{

    /**
     * @return string
     */
    private function generatePassword(): string
    {
        $password = '';
        $chars['upperCase'] = range('A', 'Z');
        $chars['lowerCase'] = range('a', 'z');
        $chars['digits'] = range(0, 9);
        $chars['special'] = str_split('#?!@$%^&*-.|/<>=_\'":;,');

        $password .= $this->getRandChar($chars['upperCase'])
            . $this->getRandChar($chars['lowerCase'])
            . $this->getRandChar($chars['digits'])
            . $this->getRandChar($chars['special']);

        while (strlen($password) < Admin::PASSWORD_MIN_LENGTH) {
            $password .= $this->getRandChar($chars[array_rand($chars)]);
        }

        return str_shuffle($password);
    }

    /**
     * @param array $chars
     * @return mixed
     */
    private function getRandChar(array $chars)
    {
        return $chars[mt_rand(0, count($chars) - 1)];
    }
}
