<?php


namespace App\Repositories;


use App\Models\Wallet;
use App\Repositories\Base\Repository;

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
            'address' => $params['contract_user_base58_address'],
            'amount_transfers' => $params['amount_transfers'] ?? 0,
            'profit_referrals' => $params['profit_referrals'] ?? 0,
            'profit_reinvest' => $params['profit_reinvest'] ?? 0,
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
