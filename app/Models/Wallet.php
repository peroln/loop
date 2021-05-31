<?php

namespace App\Models;

use App\Models\Service\FinancialTransaction;
use App\Models\Service\Platform;
use App\Models\Service\Reactivation;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'id',
        'user_id',
        'contract_user_id',
        'coin',
        'address',
        'amount_transfers',
        'profit_referrals',
        'profit_reinvest',
        'balance',
        'referral_link',
        'created_at',
        'updated_at',
    ];
    const CREATED_AT = null;

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return HasMany
     */
    public function commandRefRequests(): HasMany
    {
        return $this->hasMany(CommandRefRequest::class);
    }

    /**
     * @return HasMany
     */
    public function platforms(): HasMany
    {
        return $this->hasMany(Platform::class);
    }

    /**
     * @return HasMany
     */
    public function reactivations(): HasMany
    {
        return $this->hasMany(Reactivation::class);
    }

    /**
     * @return BelongsToMany
     */
    public function commands(): BelongsToMany
    {
        return $this->belongsToMany(Command::class);
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return HasMany
     */
    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }
}
