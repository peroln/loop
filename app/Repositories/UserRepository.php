<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Base\Repository;
use Faker\Factory;
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
        /*$params = [
            "referrer_id": "1",
            "contract_user_id": "2",
            "referrer_cache_address": "0x669d140f1eb8049bd3ad85f0152643333b491b14",
            "contract_user_cache_address": "0xe8ac7cfeb388ef6b34c3f23842ac526ec20fc3b7",
            "transaction_id": "ed45dd66da3198f2754e10233f50ae586d84a43dd1c679149e4a6e5b11519ba3"
        ]*/
        $user_data_params = [
            'user_name' => Factory::create()->userName,
            'avatar' => '/some-image.jpg',
            'blocked_faq' => false,
            'lang' => 'en',
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
            DB::commit();
        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }


        return [new UserResource($user), auth()->fromUser($wallet)];


    }
}
