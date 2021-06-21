<?php

namespace App\Models\Service;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Platform extends Model
{
    use HasFactory;

    const CREATED_AT = null;
    protected $fillable = [
        'platform_level_id',
        'wallet_id',
        'activated',
        'created_at',
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

    /**
     * @param  int  $count_subscribers
     *
     * @return int|null
     */
    public function enumeratorRestSubscribers(int $count_subscribers = 3): int|null
    {
        $arr_not_fill_platform = Platform::where('platform_level_id', $this->platform_level_id)->has('wallets', '<', $count_subscribers)->withCount('wallets')->get();
        $flag                  = 0;
        foreach ($arr_not_fill_platform as $key => $platform) {

            if ($platform->wallets_count === 2) {
                $flag       = 1;
                $rest_count = 0;
            }
            if ($platform->wallets_count === 1) {
                $flag       = 2;
                $rest_count = 0;
            }
            if ($platform->wallets_count === 0 && $key <= 1) {
                $rest_count = $flag;
            }
            if ($platform->wallets_count === 0 && $key > 1) {
                $rest_count = $rest_count + $count_subscribers;
            }
            if ($platform->id === $this->id) {
                return $rest_count;
            }
        }
        return null;
    }
    public function scopeFilled($query)
    {
        return $query->whereActive(0);
    }
}
