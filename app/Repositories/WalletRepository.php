<?php


namespace App\Repositories;


use App\Models\Wallet;
use App\Repositories\Base\Repository;
use Illuminate\Support\Arr;

class WalletRepository extends Repository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Wallet::class;
    }

    /**
     * @param array $params
     * @return array
     */
    public function createWalletDataParams(array $params): array
    {
        return [
            'coin' => 'trx',
            'address' => $params['contract_user_base58_address'] ?? '',
            'amount_transfers' => $params['amount_transfers'] ?? 0,
            'profit_referrals' => $params['profit_referrals'] ?? 0,
            'profit_reinvest' => $params['profit_reinvest'] ?? 0,
            'balance' => $params['balance'] ?? 0,
            'referral_link' => $params['referral_link'] ?? null,
            'contract_user_id' => $params['contract_user_id'] ?? 1,
            'created_at' => Arr::get($params,'block_timestamp')
        ];
    }

    /**
     * @param string $column
     * @param string|int $value
     * @return bool
     */
    public function exist(string $column, string|int $value): bool
    {
        try {
            $model = $this->findByOrFail($column, $value);
            if ($model) {
                return true;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }
}
