<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Base\Repository;
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
     * @return string
     * @throws \Throwable
     */
    public function createWithWallet(array $params): string
    {

        DB::beginTransaction();
        try {
            $user_data_params = $this->createUserDataParams($params);
            $user = $this->create($user_data_params);

            $wallet_data_params = $this->createWalletDataParams($params);
            $wallet = $user->wallet()->create($wallet_data_params);

            $transaction_data_params = $this->createTransactionDataParams($params);
            $transaction = $wallet->transactions()->create($transaction_data_params);

            $transaction_events_data_params = $this->createTransactionEventDataParams($params);
            $transaction->transactionEvents()->create($transaction_events_data_params);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return  auth()->fromUser($wallet);
    }

    /**
     * @param array $params
     * @return array
     */
    private function createUserDataParams(array $params): array
    {
        return [
            'user_name' => $params['user_name'] ?? 'Default User',
            'avatar' => '/some-image.jpg',
            'blocked_faq' => false,
            'lang' => $params['lang'] ?? 'en',
            'this_referral' => $params['referrer_id'] ?? 1
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function createWalletDataParams(array $params): array
    {
        return [
            'coin' => 'trx',
            'address' => $params['contract_user_base58_address'],
            'amount_transfers' => $params['amount_transfers'] ?? 0,
            'profit_referrals' => $params['profit_referrals'] ?? 0,
            'profit_reinvest' => $params['profit_reinvest'] ?? 0,
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    private function createTransactionDataParams(array $params): array
    {
        return [
            'base58_id' => $params['base58_id'],
            'hex' => $params['hex'],
            'blockNumber' => $params['block_number'],
            'model_service' => $params['model_service'] ?? ''
        ];
    }

    private function createTransactionEventDataParams(array $params): array
    {
        return [
            "referrer_id" => $params['referrer_id'] ?? 1,
            "contract_user_id" => $params['contract_user_id'] ?? 1,
            "referrer_base58_address" => $params['referrer_base58_address'] ?? 1,
            "contract_user_base58_address" => $params['contract_user_base58_address'] ?? 1,
            "block_number" => $params['block_number'] ?? 1,
            "block_timestamp" => $params['block_timestamp'] ?? 1,
            "event_name" => $params['event_name'] ?? '',

        ];
    }
}
