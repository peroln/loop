<?php


namespace App\Services\EventsHandlers;


use App\Models\Service\Platform;
use App\Models\Transaction;
use App\Models\TransactionEvent;
use App\Models\Wallet;
use App\Services\TronService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class FinancialAccountingTransfer extends BaseEventsHandler
{
    const EVENT_NAME = 'ReferralPaymentTransfer';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {
        try {

            $base58_id = $this->hexString2Base58(Arr::get($event, 'transaction_id', ''));
            $hex = Arr::get($event, 'transaction_id');

            $block_number = Arr::get($event, 'block_number');
            $block_timestamp = Arr::get($event, 'block_timestamp');
            $event_name = Arr::get($event, 'event_name');

            $amount = Arr::get($event, 'result.amount');
            $count_commission = Arr::get($event, 'result.fee');
            $receiver_amount = $this->hexString2Base58(Arr::get($event, 'result.to', ''));
            $receiver_commission = $this->hexString2Base58(Arr::get($event, 'result.feeReceiver', ''));

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
        return compact(
            'receiver_amount',
            'receiver_commission',
            'count_commission',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex',
            'amount',
        );
    }

    /**
     * @param array $params
     */
    public
    function createNewResource(array $params): void
    {
        try {
            $receiver_amount = Wallet::where('address', Arr::get($params, 'receiver_amount'))->firstOrFail();
            $receiver_commission= Wallet::where('address', Arr::get($params, 'receiver_commission'))->firstOrFail();
            $receiver_amount->amount_transfers += Arr::get($params, 'amount', 0);
            $receiver_commission->profit_referrals += Arr::get($params, 'count_commission', 0);
            $receiver_amount->save();
            $receiver_commission->save();

            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $receiver_amount->id,
                'base58_id'     => Arr::get($params, 'base58_id'),
                'hex'           => Arr::get($params, 'hex'),
                'model_service' => TronService::class
            ]);

            TransactionEvent::create([
                "transaction_id"               => $transaction->id,
                "referrer_base58_address"      => Arr::get($params, 'receiver_commission'),
                "contract_user_base58_address" => Arr::get($params, 'receiver_amount'),
                'block_number'                 => Arr::get($params, 'block_number'),
                'block_timestamp'              => Arr::get($params, 'block_timestamp'),
                'event_name'                   => Arr::get($params, 'event_name'),
            ]);
        } catch (\Throwable $exception) {
            Log::info(Arr::get($params, 'receiver_commission'));
            Log::info(Arr::get($params, 'receiver_amount'));
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }
    }
}
