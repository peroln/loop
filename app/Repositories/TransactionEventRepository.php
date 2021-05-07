<?php


namespace App\Repositories;


use App\Models\TransactionEvent;
use App\Repositories\Base\Repository;

class TransactionEventRepository extends Repository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return TransactionEvent::class;
    }
    /**
     * @param array $params
     * @return array
     */
    public function createTransactionEventDataParams(array $params): array
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
