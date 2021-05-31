<?php


namespace App\Repositories;


use App\Models\TransactionEvent;
use App\Repositories\Base\Repository;
use Illuminate\Support\Arr;

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
            "referrer_id" => Arr::get($params, 'referrer_id', 1),
            "contract_user_id" => Arr::get($params, 'contract_user_id', 1),
            "referrer_base58_address" => Arr::get($params, 'referrer_base58_address', 1),
            "contract_user_base58_address" => Arr::get($params, 'contract_user_base58_address', 1),
            "block_number" => Arr::get($params, 'block_number', 1),
            "block_timestamp" => Arr::get($params, 'block_timestamp'),
            "event_name" => Arr::get($params, 'event_name', '')
        ];
    }
}
