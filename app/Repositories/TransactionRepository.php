<?php


namespace App\Repositories;


use App\Models\Transaction;
use App\Repositories\Base\Repository;

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
            'base58_id' => $params['base58_id'],
            'hex' => $params['hex'],
            'blockNumber' => $params['block_number'],
            'model_service' => $params['model_service'] ?? ''
        ];
    }

    /**
     * @return array
     */
    public function retrieveHexIdRegistration(): array
    {
        return $this->getModel()->whereHas('transactionEvents', fn($q) => $q->where('event_name', 'Registration'))->pluck('hex')->toArray();
    }

}
