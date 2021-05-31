<?php


namespace App\Http\Controllers\Service;


use App\Models\Service\League;
use App\Models\Wallet;
use App\Services\EventsHandlers\FinancialAccountingTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CabinetController
{
    public function mainInformation()
    {
        $all_wallets = Wallet::get();
        $all_count = $all_wallets->count();
        $users_invited_last_24_hour = $all_wallets->whereBetween('created_at', [now()->subDay(), now() ])->where('id','!=', 1)->count();
        $all_trx = $all_wallets->sum('amount_transfers');
       return response()->json(compact('all_count', 'users_invited_last_24_hour', 'all_trx'));
    }

    public function leagueRating(){
       /* return League::with(['platformLevels.platforms.wallet.transactions.transactionEvents' => function($q){
           return $q->where('event_name', FinancialAccountingTransfer::EVENT_NAME);
        }])->get();*/
        try{
            return League::join('platform_levels', 'leagues.id', '=', 'platform_levels.league_id')
                ->join('platforms', 'platform_levels.id', '=', 'platforms.platform_level_id')
                ->join('wallets', 'platforms.wallet_id', '=', 'wallets.id')
                ->select(
                    'leagues.*',
                    'platform_levels.name AS level_name',
                    'platforms.wallet_id',
                    'wallets.address'
                )
                ->get();
        }catch(\Throwable $e){
            Log::error($e->getMessage());
        }

    }
}
