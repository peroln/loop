<?php


namespace App\Services;


use App\Models\Service\League;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Builder;

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
    public function RatingLeague(string $period = 'month')
    {
        $data_start = match ($period) {
            'year' => now()->startOfYear(),
            'month' => now()->startOfMonth(),
            'week' => now()->startOfWeek(),
            'day' => now()->startOfDay()
        };
        return $this->createRequest($data_start, now(), 10);

    }

    /**
     * @return array
     */
    public function LeagueDesk(): array
    {
        $start_date = now()->startOfMonth()->subMonth();
        $end_date = now()->endOfMonth()->subMonth();
        $limit = 3;
        $leagues_info = $this->createRequest($start_date, $end_date, $limit);
        $users_info = User::withCount(['subscribers' => function ($q) use ($start_date, $end_date) {
            $q->whereHas('wallet', fn($q) => $q->whereBetween('created_at', [$start_date, $end_date]));
        }])->orderByDesc('subscribers_count')->limit($limit)->get();
        $month = now()->subMonth()->format('F');
        $year = now()->subMonth()->format('Y');
        return compact('month', 'year', 'users_info', 'leagues_info');
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $limit
     * @return mixed
     */
    private function createRequest($start_date, $end_date, $limit)
    {
        try {
            $arr = League::leftJoin('platform_levels', 'leagues.id', '=', 'platform_levels.league_id')
                ->leftJoin('platforms', 'platform_levels.id', '=', 'platforms.platform_level_id')
                ->leftJoin('wallets', 'platforms.wallet_id', '=', 'wallets.id')
                ->leftJoin('financial_transactions', function ($q) use ($start_date, $end_date) {
                    $q->on('wallets.id', '=', 'financial_transactions.wallet_id')
                        ->whereBetween('financial_transactions.created_at', [$start_date, $end_date]);
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

            return $arr1->map(function ($item, $key) use ($limit) {
                return $item->map(function ($i, $k) use ($limit) {
                    return [
                        'contract_user_id' => $k,
                        'sum'              => $i->sum('amount')
                    ];
                })
                    ->sortByDesc('sum')
                    ->values()
                    ->take($limit);
            });

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

}
