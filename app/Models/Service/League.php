<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function platformLevels(): HasMany
    {
        return $this->hasMany(PlatformLevel::class);
    }
}
