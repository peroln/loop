<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use Carbon\Carbon;

class WalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 30; $i++) {
            $profit_referrals = random_int(250, 5000);
            $profit_reinvest = random_int(250, 5000);
            $erc20Address = Str::random(32);

            $wallets[] = [
                'user_id'           => $i,
                'coin'              => 'trx',
                'address'           => $erc20Address,
                'amount_transfers'  => $profit_referrals + $profit_reinvest,
                'profit_referrals'  => $profit_referrals,
                'profit_reinvest'   => $profit_reinvest,
                'created_at'        => Carbon::now()->addMinutes($i)->format('Y-m-d H:i:s'),
                'updated_at'        => Carbon::now()->addMinutes($i + 2)->format('Y-m-d H:i:s'),

            ];
        }

        DB::table('wallets')->insert($wallets);
    }
}
