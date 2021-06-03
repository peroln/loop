<?php

namespace App\Models\Service;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_level_id',
        'wallet_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function wallets()
    {
        return $this->belongsToMany(Wallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function platformLevel()
    {
        return $this->belongsTo(PlatformLevel::class);
    }

    /**
     * @return int
     */
    public function totalSubscribersByLevel(): int
    {
        return DB::table('platforms')
            ->join('platform_wallet', 'platforms.id', '=', 'platform_wallet.platform_id')
            ->where('platforms.platform_level_id', '=', $this->platform_level_id)
            ->where('platforms.wallet_id', '=', $this->wallet_id)
            ->select('platform_wallet.wallet_id')
            ->count();
    }
}
