<?php


namespace App\Services\EventsHandlers;


use App\Events\CreatedPlatformEvent;
use App\Models\Service\Platform;
use App\Models\Transaction;
use App\Models\TransactionEvent;
use App\Models\Wallet;
use App\Services\TronService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PlatformCreateEventHandler extends BaseEventsHandler
{
    const EVENT_NAME = 'Upgrade';

    public function createNewResource(array $params): void
    {
        try {
            $wallet_id = Wallet::where('address', Arr::get($params, 'contract_user_base58_address'))->firstOrFail()->id;
            if($wallet_id === 2){
                Log::info(__METHOD__ . 'Wallet 2');
            }

            $platform = Platform::create([
                'wallet_id'         => $wallet_id,
                'platform_level_id' => Arr::get($params, 'platform'),
                'activated' => true,
                'created_at'        => Arr::get($params, 'block_timestamp'),
            ]);
            CreatedPlatformEvent::dispatch($platform);

            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $wallet_id,
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
            Log::info(Arr::get($params, 'contract_user_base58_address'));
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }

    }


    /**
     * @param array $event
     * @return array
     */
    public function extractDataFromTransaction(array $event): array
    {
        try {
            $referrer_base58_address = $this->hexString2Base58(Arr::get($event, 'result.referrer', ''));
            $contract_user_base58_address = $this->hexString2Base58(Arr::get($event, 'result.user', ''));
            $base58_id = $this->hexString2Base58(Arr::get($event, 'transaction_id', ''));
            $hex = Arr::get($event, 'transaction_id');
            $block_number = Arr::get($event, 'block_number');
            $block_timestamp = date('Y-m-d H:i:s', (int)Arr::get($event, 'block_timestamp') / 1000);
            $event_name = Arr::get($event, 'event_name');
            $platform = Arr::get($event, 'result.platform');

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
        );
    }
}
