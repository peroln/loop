<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'base58_id',
        'hex',
        'block_timestamp',
        'blockNumber',
        'ref_block_hash',
        'energy_fee',
        'energy_usage_total',
        'fee_limit',
        'call_value',
        'expiration',
        'model_service',
        'timestamp'
    ];

    public function transactionEvents()
    {
        return $this->hasMany(TransactionEvent::class);
    }
}
