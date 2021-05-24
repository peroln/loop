<?php


namespace App\Services\EventsHandlers;


use App\Models\Transaction;
use App\Models\TransactionEvent;
use App\Models\Wallet;
use App\Services\TronService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PlatformSubscriberEventHandler extends BaseEventsHandler
{
    const EVENT_NAME = 'NewUserPlace';

    public function createNewResource(array $params): void
    {
        try {
            $referer_wallet = Wallet::where('address', Arr::get($params, 'referrer_base58_address'))->firstOrFail();
            $referer_current_platform = $referer_wallet->platforms()->firstOrCreate([
                'platform_level_id' => Arr::get($params, 'platform'),
                'active'            => 1
            ]);
            $subscriber_wallet_id = Wallet::where('address', Arr::get($params, 'contract_user_base58_address'))->firstOrFail()->id;

            $referer_current_platform->wallets()->attach(
                $subscriber_wallet_id,
                ['place' => Arr::get($params, 'place')]
            );

            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $referer_wallet->id,
                'base58_id'     => Arr::get($params, 'contract_user_base58_address'),
                'hex'           => Arr::get($params, 'hex'),
                'model_service' => TronService::class
            ]);

            TransactionEvent::create([
                "transaction_id"               => $transaction->id,
                "referrer_base58_address"      => Arr::get($params, 'referrer_base58_address'),
                "contract_user_base58_address" => Arr::get($params, 'contract_user_base58_address'),
                'block_number'                 => Arr::get($params, 'block_number'),
                'block_timestamp'              => Arr::get($params, 'block_timestamp'),
                'event_name'                   => Arr::get($params, 'event_name'),
            ]);
        } catch (\Throwable $exception) {
            Log::info(Arr::get($params, 'referrer_base58_address'));
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }


    }

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {

        try {
            $referrer_base58_address = $this->hexString2Base58(Arr::get($event, 'result.owner', ''));
            $contract_user_base58_address = $this->hexString2Base58(Arr::get($event, 'result.user', ''));
            $base58_id = $this->hexString2Base58(Arr::get($event, 'transaction_id'));
            $hex = Arr::get($event, 'transaction_id');
            $block_number = Arr::get($event, 'block_number');
            $block_timestamp = Arr::get($event, 'block_timestamp');
            $event_name = Arr::get($event, 'event_name');
            $platform = Arr::get($event, 'result.platform');
            $place = Arr::get($event, 'result.place');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
        return compact(
            'referrer_base58_address',
            'contract_user_base58_address',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex',
            'platform',
            'place'
        );
    }
}
