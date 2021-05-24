<?php

namespace App\Models\Service;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_level_id',
        'wallet_id',
        'count'
    ];

    public function platformLevel()
    {
        return $this->belongsTo(PlatformLevel::class);
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
