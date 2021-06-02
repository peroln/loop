<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Repositories\Base\Repository;
use Illuminate\Support\Arr;

class TransactionRepository extends Repository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Transaction::class;
    }

    /**
     * @param array $params
     * @return array
     */
    public function createTransactionDataParams(array $params): array
    {
        return [
            'base58_id' => Arr::get($params, 'base58_id', ''),
            'hex' => Arr::get($params, 'hex', ''),
            'call_value' => Arr::get($params, 'call_value', 0),
            'blockNumber' => Arr::get($params, 'block_number', 0),
            'model_service' => Arr::get($params, 'model_service', ''),
            'block_timestamp' => Arr::get($params, 'block_timestamp')
        ];
    }

    /**
     * @return array
     */
    public function retrieveHexIdRegistration(array|string $event_name): array
    {
        if(!is_array($event_name)){
            $event_name = Arr::wrap($event_name);
        }
        return $this->getModel()->whereHas('transactionEvents', fn($q) => $q->whereIn('event_name', $event_name))->pluck('hex')->unique()->toArray();
    }

}
