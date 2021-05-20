<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reactivation extends Model
{
    use HasFactory;
    protected $fillable = [
        'platform_level_id',
        'wallet_id',
        'count'
    ];
}
