<?php


namespace App\Services\EventsHandlers;


use App\Events\ReactivationPlatform;
use App\Models\Service\Overflow;
use App\Events\Overflow as OverflowEvent;
use App\Models\Service\Platform;
use App\Models\Service\Reactivation;
use App\Models\Transaction;
use App\Models\TransactionEvent;
use App\Models\Wallet;
use App\Services\TronService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class OverflowPlatformEvent extends BaseEventsHandler
{
    const EVENT_NAME = 'MissedEthPayment';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {
        try {
            $receiver = $this->hexString2Base58(Arr::get($event, 'result.receiver'));
            $from = $this->hexString2Base58(Arr::get($event, 'result.from'));

            $base58_id = $this->hexString2Base58(Arr::get($event, 'transaction_id'));
            $hex = Arr::get($event, 'transaction_id');

            $block_number = Arr::get($event, 'block_number');
            $block_timestamp = Arr::get($event, 'block_timestamp');
            $event_name = Arr::get($event, 'event_name');
            $platform = Arr::get($event, 'result.platform');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
        return compact(
            'receiver',
            'from',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex',
            'platform',
        );

    }

    /**
     * @param array $params
     */
    public function createNewResource(array $params): void
    {
        try {
            $wallet = Wallet::where('address', Arr::get($params, 'receiver'))->firstOrFail();

            $overflow = Overflow::firstOrNew([
                'platform_level_id' => Arr::get($params, 'platform'),
                'wallet_id'         => $wallet->id,
            ]);
            $overflow->count++;
            $overflow->save();

            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $wallet->id,
                'base58_id'     => Arr::get($params, 'base58_id'),
                'hex'           => Arr::get($params, 'hex'),
                'model_service' => TronService::class
            ]);

            TransactionEvent::create([
                "transaction_id"               => $transaction->id,
                "contract_user_base58_address" => Arr::get($params, 'from'),
                'block_number'                 => Arr::get($params, 'block_number'),
                'block_timestamp'              => Arr::get($params, 'block_timestamp'),
                'event_name'                   => Arr::get($params, 'event_name'),
            ]);

            OverflowEvent::dispatch($wallet,$overflow);
        } catch (\Throwable $exception) {
            Log::info(Arr::get($params, 'receiver'));
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }

    }
}
