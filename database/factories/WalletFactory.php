<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Services\Blockchain\ContractCallService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        try{
            return [

                'coin'             => 'trx',
                'address'          => (new ContractCallService())->getFirstUser(),  // \Str::random(32),
                'amount_transfers' => 0, // $profit_reinvest + $profit_referrals,
                'profit_referrals' => 0, // $profit_referrals,
                'profit_reinvest'  => 0, // $profit_reinvest,
                'referral_link'    => 'cb995ad7a281845e60a02b3d4cfd7eaac43558cb',
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ];
        }catch(\Throwable $e){
            Log::error($e->getMessage());
        }

    }
}
