<?php

namespace App\Http\Resources\Admin\Management\Users;

use App\Enums\BaseAppEnum;
use App\Services\User\TransactionsService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $pagination = $request->input('pagination') ?? BaseAppEnum::DEFAULT_PAGINATION;
        $transactions = $this->transactions;
        resolve(TransactionsService::class)->filterRejectedBlockChainTx($transactions);
        resolve(TransactionsService::class)->filterHideSomeTypesOfTransactions($transactions);

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'transactions' => $transactions->paginate($pagination),
        ];
    }

}
