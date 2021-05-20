<?php

namespace App\Models\Service;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;
    protected $fillable = [
      'platform_level_id',
      'wallet_id'
    ];

    public function wallets()
    {
        return $this->belongsToMany(Wallet::class);
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
