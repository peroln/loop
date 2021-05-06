<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'hex' => $this->hex,
            'base58_id' => $this->base58_id,
            'model_service' => $this->model_service,
            'wallet_id' => $this->wallet_id,
            'block_timestamp' => $this->block_timestamp,
            'timestamp' => $this->timestamp,
            'events' =>  TransactionEventResource::collection($this->whenLoaded('transactionEvents')),
        ];
    }
}
