<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends BaseModel
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'user_name',
        'avatar',
        'blocked_faq',
        'lang',
        'referral',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }
}
