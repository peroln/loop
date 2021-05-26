<?php


namespace App\Services\EventsHandlers;


use App\Events\ReactivationPlatform;
use App\Models\Service\Platform;
use App\Models\Service\Reactivation;
use App\Models\Transaction;
use App\Models\TransactionEvent;
use App\Models\Wallet;
use App\Services\TronService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class PlatformReactivationEvent extends BaseEventsHandler
{
    const EVENT_NAME = 'Reinvest';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {
        try {
            $contract_user_base58_address = $this->hexString2Base58(Arr::get($event, 'result.caller'));
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
            'contract_user_base58_address',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex',
            'platform',
        );
    }

    /**
     * @param array $param
     */
    public function createNewResource(array $params): void
    {
        try {
            $wallet_id = Wallet::where('address', Arr::get($params, 'contract_user_base58_address'))->firstOrFail()->id;

            $platform = Platform::create([
                'wallet_id'         => $wallet_id,
                'platform_level_id' => Arr::get($params, 'platform')
            ]);

            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $wallet_id,
                'base58_id'     => Arr::get($params, 'contract_user_base58_address'),
                'hex'           => Arr::get($params, 'hex'),
                'model_service' => TronService::class
            ]);

            TransactionEvent::create([
                "transaction_id"               => $transaction->id,
//            "referrer_base58_address" => Arr::get($params, 'referrer_base58_address'),
                "contract_user_base58_address" => Arr::get($params, 'contract_user_base58_address'),
                'block_number'                 => Arr::get($params, 'block_number'),
                'block_timestamp'              => Arr::get($params, 'block_timestamp'),
                'event_name'                   => Arr::get($params, 'event_name'),
            ]);

            $reactivation_model = Reactivation::firstOrNew([
                'platform_level_id' => Arr::get($params, 'platform'),
                'wallet_id'         => $wallet_id,
            ]);
            $reactivation_model->count++;
            $reactivation_model->save();

            ReactivationPlatform::dispatch($platform);
        } catch (\Throwable $exception) {
            Log::info(Arr::get($params, 'contract_user_base58_address'));
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }

    }
}
