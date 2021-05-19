<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Command extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'status'
    ];

    public function wallets()
    {
        return $this->belongsToMany(Wallet::class)->withPivot('order');
    }
}
