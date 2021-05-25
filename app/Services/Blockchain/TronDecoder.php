<?php declare(strict_types=1);

namespace App\Services\Blockchain;

use App\Services\Helpers\Base58;

/**
 * Trait TronDecoder
 * @package App\Services\Blockchain
 * @description Decode tron messages
 * @source https://github.com/tronprotocol/documentation/blob/master/TRX_CN/index.php
 */
trait TronDecoder
{
    use Base58;

    public function base58CheckDe(string $hexAddress): ?string
    {
        $address = $this->base58Decode($hexAddress);
        $size = strlen($address);
        if ($size !== 25) {
            return null;
        }
        $checksum = substr($address, 21);
        $address = substr($address, 0, 21);
        $hash0 = hash("sha256", $address);
        $hash1 = hash("sha256", hex2bin($hash0));
        $checksum0 = substr($hash1, 0, 8);
        $checksum1 = bin2hex($checksum);
        if (strcmp($checksum0, $checksum1)) {
            return null;
        }
        return $address;
    }

    function base58CheckEn($address): string
    {
        $hash0 = hash("sha256", $address);
        $hash1 = hash("sha256", hex2bin($hash0));
        $checksum = substr($hash1, 0, 8);
        $address = $address.hex2bin($checksum);
        return $this->base58Encode($address);
    }

    /**
     * @param $hexString
     * @return string
     * @source https://github.com/kushkamisha/tron-format-address/blob/master/lib/crypto.ts
     */
    function hexString2Base58($hexString): string
    {
        $substr = substr($hexString, 2, strlen($hexString));
        $address = "41$substr";
        $address = hex2bin($address);
        return $this->base58CheckEn($address);
    }

    /**
     * @param $base58add
     * @return string
     * @source https://github.com/tronprotocol/documentation/blob/master/TRX_CN/index.php
     */
    function base58check2HexString($base58add)
    {
        $address = $this->base58CheckDe($base58add);
        return bin2hex($address);
    }

}
