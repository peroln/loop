<?php

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Interface DeviceCheckInterface
 *
 * @package App\Models\Helpers
 */
interface DeviceCheckInterface
{
    /**
     * Get base user for device
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $ownerId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnerDevice(Builder $query, int $ownerId): Builder;

    /**
     * Return code hash
     *
     * @return string|null
     */
    public function getCodeHash(): ?string;

    /**
     * Check is device verified
     *
     * @return bool
     */
    public function isVerified(): bool;
}
