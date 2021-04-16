<?php

declare(strict_types=1);

namespace App\Traits;

/**
 * Trait NumericUUID
 *
 * @package App\Traits
 */
trait NumericUUID
{
    private static string $UUID_FORMAT = "%04s-%04d-%04d-%04d";

    /**
     * @param int $size
     * @return string
     * @throws \Exception
     */
    private static function randomNumber(int $size = 4): string
    {
        $randomNum = random_int(0, (int) ('9' . round(microtime(true))));

        return str_pad((string) $randomNum, $size, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public static function generateUUID(): string
    {
        return sprintf(
            self::$UUID_FORMAT,
            self::generateCode(),
            self::generateCode(),
            self::generateCode(),
            self::generateCode()
        );
    }

    /**
     * @return int
     */
    private static function generateNumberFromHexDec(): int
    {
        return hexdec(uniqid('', false));
    }

    /**
     * @return int
     */
    private static function generateNumberFromUniqId(): int
    {
        return abs(crc32(uniqid('', true)));
    }

    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function generateCode(int $length = 4): string
    {
        $randomNum = sprintf("%0{$length}d", random_int(0, (10 ** $length) - 1));

        return str_pad($randomNum, $length, '0', STR_PAD_LEFT);
    }
}
