<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
//        $profit_reinvest = Arr::random(range(10, 100, 10));
//        $profit_referrals = Arr::random(range(10, 100, 10));
        return [

            'coin'             => 'trx',
            'address'          => 'TQmfde23Z7wsW7D4zmKnkQXGMCivGSDoj4',  // \Str::random(32),
            'amount_transfers' => 0, // $profit_reinvest + $profit_referrals,
            'profit_referrals' => 0, // $profit_referrals,
            'profit_reinvest'  => 0, // $profit_reinvest,
            'referral_link'    => '669d140f1eb8049bd3ad85f0152643333b491b14',
            'created_at'       => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'       => Carbon::now()->format('Y-m-d H:i:s'),
        ];
    }
}
