<?php

namespace App\Models;
use App\Models\BaseModel;

class Users extends BaseModel
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
}
