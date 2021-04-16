<?php

namespace App\Models\Helpers;

/**
 * Interface Google2FAInterface
 *
 * @package App\Models\Helpers
 */
interface Authy2FAInterface
{
    /**
     * Disable 2fa for model instance
     */
    public function disable2FA(): void;

    /**
     * Enable 2fa for model instance
     */
    public function enable2FA(): void;

    /**
     * Update authy id when registering 2fa
     */
    public function updateAuthyId($authy_id): void;
}
