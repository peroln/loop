<?php

namespace App\Models;

use App\Events\UserCreatedEvent;
use App\Models\User\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;
    protected $dispatchesEvents = [
        'created' => UserCreatedEvent::class,
    ];
    protected $fillable = [
        'id',
        'contract_user_id',
        'user_name',
        'avatar',
        'blocked_faq',
        'language_id',
        'this_referral',
        'created_at',
        'updated_at',
    ];
    const CREATED_AT = null;

    /**
     * @return HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * @return BelongsToMany
     */
    public function statuses(): BelongsToMany
    {
        return $this->belongsToMany(Status::class);
    }

    /**
     * @return BelongsTo
     */
    public function referral(): BelongsTo
    {
        return $this->belongsTo(self::class, 'this_referral', 'id');
    }

    /**
     * @return HasMany
     */
    public function subscribers(): HasMany
    {
        return $this->hasMany(self::class, 'this_referral', 'id');
    }

}
