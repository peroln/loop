<?php


namespace App\Services;


use App\Models\Service\League;
use App\Models\Service\TargetIncome;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Builder;

class CabinetService
{
    public function mainInfoCabinet(): array
    {
        $all_wallets                = Wallet::whereNotin('id', [1])->get();
        $all_count                  = $all_wallets->count();
        $users_invited_last_24_hour = $all_wallets->whereBetween('created_at', [now()->subDay(), now()])->where('id', '!=', 1)->count();
        $all_trx                    = $all_wallets->sum('amount_transfers');
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
        $start_date   = now()->startOfMonth()->subMonth();
        $end_date     = now()->endOfMonth()->subMonth();
        $limit        = 3;
        $leagues_info = $this->createRequest($start_date, $end_date, $limit);
        $users_info   = User::whereNotIn('id', [1])->withCount([
            'subscribers' => function ($q) use ($start_date, $end_date) {
                $q->whereHas('wallet', fn($q) => $q->whereBetween('created_at', [$start_date, $end_date]));
            },
        ])->orderByDesc('subscribers_count')->limit($limit)->get();
        $month        = now()->subMonth()->format('F');
        $year         = now()->subMonth()->format('Y');
        return compact('month', 'year', 'users_info', 'leagues_info');
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $limit
     *
     * @return mixed
     */
    private function createRequest($start_date, $end_date, $limit)
    {
        try {
            $arr = League::leftJoin('platform_levels', 'leagues.id', '=', 'platform_levels.league_id')
                ->leftJoin('platforms', 'platform_levels.id', '=', 'platforms.platform_level_id')
                ->leftJoin('wallets', function ($join) {
                    $join->on('platforms.wallet_id', '=', 'wallets.id')
                        ->whereNotIn('wallets.id', [1]);
                })
                ->leftJoin('financial_transactions', function ($q) use ($start_date, $end_date) {
                    $q->on('wallets.id', '=', 'financial_transactions.wallet_id')
                        ->where('target_income_id', TargetIncome::where('name', 'account')->firstOrFail()->id)
                        ->whereBetween('financial_transactions.created_at', [$start_date, $end_date]);

                })
                ->select(
                    'leagues.name',
                    'wallets.contract_user_id',
                    'transaction_event_id',
                    'amount'
                )
                ->groupBy(
                    'leagues.name',
                    'wallets.contract_user_id',
                    'transaction_event_id',
                    'amount'
                )
                ->orderBy('leagues.name')
                ->orderBy('wallets.contract_user_id')
                ->get();

            $expel_arr = $this->expelFromLowestLeagues($arr);

            $arr1 = $expel_arr->groupBy([
                'name', function ($item) {
                    return $item['contract_user_id'];
                },
            ]);

            return $arr1->map(function ($item) use ($limit) {
                return $item->map(function ($i, $k) use ($limit) {
                    if (!$k || !$i->sum('amount')) {
                        return [];
                    }
                    return [
                        'contract_user_id' => $k,
                        'sum'              => $i->sum('amount'),
                    ];
                })->reject(function ($arr) {
                    return !count($arr);
                })
                    ->sortByDesc('sum')
                    ->values()
                    ->take($limit);
            });

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }

    }

    /**
     * @param  Collection  $arr_request
     *
     * @return Collection
     */
    private function expelFromLowestLeagues(Collection $arr_request): Collection
    {
        $arr_users_id['Diamond']  = $arr_request->where('name', 'Diamond')
            ->unique('contract_user_id')
            ->pluck('contract_user_id')
            ->reject(fn($value) => $value == null);
        $arr_users_id['Platinum'] = $arr_request->where('name', 'Platinum')
            ->unique('contract_user_id')
            ->pluck('contract_user_id')
            ->reject(fn($value) => $value == null);;
        $arr_users_id['Gold'] = $arr_request->where('name', 'Gold')
            ->unique('contract_user_id')
            ->pluck('contract_user_id')
            ->reject(fn($value) => $value == null);;
        $arr_users_id['Silver'] = $arr_request->where('name', 'Silver')
            ->unique('contract_user_id')
            ->pluck('contract_user_id')
            ->reject(fn($value) => $value == null);;
        $arr_users_id['Bronze'] = $arr_request->where('name', 'Bronze')
            ->unique('contract_user_id')
            ->pluck('contract_user_id')
            ->reject(fn($value) => $value == null);;

        $arr_exclude_users_id['Diamond']  = [];
        $arr_exclude_users_id['Platinum'] = $arr_users_id['Diamond']->toArray();
        $arr_exclude_users_id['Gold']     = $arr_users_id['Platinum']->merge($arr_exclude_users_id['Platinum'])->unique()->toArray();
        $arr_exclude_users_id['Silver']   = $arr_users_id['Gold']->merge($arr_exclude_users_id['Gold'])->unique()->toArray();
        $arr_exclude_users_id['Bronze']   = $arr_users_id['Silver']->merge($arr_exclude_users_id['Silver'])->unique()->toArray();

        return $arr_request->reject(function ($value) use ($arr_exclude_users_id) {
            return in_array($value->contract_user_id, $arr_exclude_users_id[$value->name]);
        });

    }
}
