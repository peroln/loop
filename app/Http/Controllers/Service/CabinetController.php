<?php


namespace App\Http\Controllers\Service;


use App\Models\Wallet;

class CabinetController
{
    public function mainInformation()
    {
        $all_wallets = Wallet::get();
        $all_count = $all_wallets->count();
        $users_invited_last_24_hour = $all_wallets->whereBetween('created_at', [now()->subMonths(1), now() ])->count();
        $all_trx = $all_wallets->sum('amount_transfers');
       return response()->json(compact('all_count', 'users_invited_last_24_hour', 'all_trx'));
    }
}
