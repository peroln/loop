<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

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

}
