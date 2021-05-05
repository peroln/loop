<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Base\Repository;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class UserRepository extends Repository
{
    public function model(): string
    {
        return User::class;
    }

    /**
     * @param string $address
     * @return UserResource
     */
    public function getUserByWallet(string $address): UserResource
    {
        return new UserResource($this->getModel()->whereHas('wallet', fn($q) => $q->where('address', $address))->first());
    }

    /**
     * @param array $params
     * @return array
     * @throws \Throwable
     */
    public function createWithWallet(array $params): array
    {
        $user_data_params = [
            'user_name' => Factory::create()->userName,
            'avatar' => '/some-image.jpg',
            'blocked_faq' => false,
            'lang' => $params['lang'] ?? 'en',
            'this_referral' => $params['referrer_id'] ?? 1
        ];
        DB::beginTransaction();
        try{

            $user = $this->create($user_data_params);

            $wallet_data_params = [
                'user_id' => $user->id,
                'coin' => 'trx',
                'address' => $params['contract_user_cache_address'], // this data is invalid hex now
                'amount_transfers' => 100, // fake value
                'profit_referrals' => 80, //fake value
                'profit_reinvest' => 20, // fake value
            ];
            $wallet = $user->wallet()->create($wallet_data_params);
            $params = Arr::except($params, 'hex');
            $wallet->transactionEvents()->create($params);
            DB::commit();
        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
        return [new UserResource($user), auth()->fromUser($wallet)];
    }
}
