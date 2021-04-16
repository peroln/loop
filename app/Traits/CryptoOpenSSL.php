<?php

namespace App\Traits;

use App\Exceptions\ApplicationException;

/**
 * Trait CryptoOpenSSL
 *
 * @package App\Traits
 */
trait CryptoOpenSSL
{
    public $cryptMethod = 'aes-256-cbc';

    /**
     * Generate secure key
     *
     * @param int $bytes
     * @return string
     */
    public function generateKey(int $bytes = 32)
    {
        return base64_encode(openssl_random_pseudo_bytes($bytes));
    }

    /**
     * Encrypt data
     *
     * @param string $data
     * @return string
     */
    public function securedEncrypt(string $data): string
    {
        $first_key = base64_decode(config('crypto_open_ssl.crypto_key_first'));
        $second_key = base64_decode(config('crypto_open_ssl.crypto_key_second'));
        $iv_length = openssl_cipher_iv_length($this->cryptMethod);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $first_encrypted = openssl_encrypt($data, $this->cryptMethod, $first_key, OPENSSL_RAW_DATA, $iv);
        $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, true);

        return base64_encode($iv . $second_encrypted . $first_encrypted);
    }

    /**
     * Decrypt data
     *
     * @param string $data
     * @return string|null
     */
    public function securedDecrypt(string $data): ?string
    {
        $first_key = base64_decode(config('crypto_open_ssl.crypto_key_first'));
        $second_key = base64_decode(config('crypto_open_ssl.crypto_key_second'));

        $mix = base64_decode($data);

        $iv_length = openssl_cipher_iv_length($this->cryptMethod);

        $iv = substr($mix, 0, $iv_length);
        $second_encrypted = substr($mix, $iv_length, 64);
        $first_encrypted = substr($mix, $iv_length + 64);

        $data = openssl_decrypt($first_encrypted, $this->cryptMethod, $first_key, OPENSSL_RAW_DATA, $iv);
        if ($data === false) {
            \Log::info("ERROR: Cannot decrypt data - ${data}");
            throw new ApplicationException('Cannot decrypt data');
        }
        $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, true);

        if (hash_equals($second_encrypted, $second_encrypted_new)) {
            return $data;
        }

        return null;
    }
}
