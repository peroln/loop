<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformLevel extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function reactivation(): HasMany
    {
        return $this->hasMany(Reactivation::class);
    }

    /**
     * @return BelongsTo
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    /**
     * @return HasMany
     */
    public function platforms(): HasMany
    {
        return $this->hasMany(Platform::class);
    }

    /**
     * @return mixed
     */
    public function getLastFilledPlatform(): mixed
    {
        $filled_platforms = $this->platforms()->filled()->orderBy('id')->get();
        return $filled_platforms->where('id', $filled_platforms->max('id'))->first();
    }
}
