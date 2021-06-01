<?php


namespace App\Services;


use App\Models\Service\League;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CabinetService
{
    public function mainInfoCabinet(): array
    {
        $all_wallets = Wallet::get();
        $all_count = $all_wallets->count();
        $users_invited_last_24_hour = $all_wallets->whereBetween('created_at', [now()->subDay(), now()])->where('id', '!=', 1)->count();
        $all_trx = $all_wallets->sum('amount_transfers');
        return [$all_count, $users_invited_last_24_hour, $all_trx];
    }

    /**
     * @return mixed
     */
    public function RatingLeague()
    {
        try {
            $arr = League::leftJoin('platform_levels', 'leagues.id', '=', 'platform_levels.league_id')
                ->leftJoin('platforms', 'platform_levels.id', '=', 'platforms.platform_level_id')
                ->leftJoin('wallets', 'platforms.wallet_id', '=', 'wallets.id')
                ->leftJoin('financial_transactions', function ($q) {
                    $q->on('wallets.id', '=', 'financial_transactions.wallet_id')
                        ->where('financial_transactions.created_at', '<', now());
                })
                ->select(
                    'leagues.name',
                    'wallets.contract_user_id',
                    DB::raw('SUM(amount) as amount')
                )
                ->groupBy(
                    'leagues.name',
                    'wallets.contract_user_id',
                    'amount',
                )
                ->orderBy('leagues.name')
                ->orderBy('wallets.contract_user_id')
                ->orderBy('amount', 'desc')
                ->get();


            $arr1 = $arr->groupBy(['name', function ($item) {
                return $item['contract_user_id'];
            }]);

            return $arr1->map(function ($item, $key) {
                return $item->map(function ($i, $k) {
                    return [
                        'contract_user_id' => $k,
                        'sum'              => $i->sum('amount')
                    ];
                })
                    ->sortByDesc('sum')
                    ->values()
                    ->take(10);
            });

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }

    }
}
