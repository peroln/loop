<?php

namespace App\Models;
use App\Models\BaseModel;

class Wallet extends BaseModel
{
    protected $table = 'wallets';

    protected $fillable = [
        'id',
        'user_id',
        'coin',
        'address',
        'amount_transfers',
        'profit_referrals',
        'profit_reinvest',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
