<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        "transaction_id",
        //        'wallet_id',
        "referrer_id",
        "contract_user_id",
        "referrer_base58_address",
        "contract_user_base58_address",
        'block_number',
        'block_timestamp',
        'event_name',
    ];

    public function referrerByAddress()
    {
        return $this->belongsTo(Wallet::class, 'referrer_base58_address', 'address');
    }
}
