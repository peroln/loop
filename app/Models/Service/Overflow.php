<?php

namespace App\Models\Service;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Overflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_level_id',
        'wallet_id',
        'count',
    ];

    /**
     * @return BelongsTo
     */
    public function platformLevel(): BelongsTo
    {
        return $this->belongsTo(PlatformLevel::class);
    }

    /**
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return BelongsToMany
     */
    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(Transaction::class);
    }
}
