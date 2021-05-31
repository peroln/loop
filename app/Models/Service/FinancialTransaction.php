<?php

namespace App\Models\Service;

use App\Models\TransactionEvent;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    protected $fillable = [
        'transaction_event_id',
        'target_income_id',
        'amount',
        'wallet_id',
        'created_at'
    ];

    /**
     * @return BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return BelongsTo
     */
    public function targetIncome(): BelongsTo
    {
        return $this->belongsTo(TargetIncome::class);
    }

    /**
     * @return BelongsTo
     */
    public function transactionEvent(): BelongsTo
    {
        return $this->belongsTo(TransactionEvent::class);
    }
}
