<?php


namespace App\Services\EventsHandlers;

use App\Models\Service\FinancialTransaction;
use App\Models\Service\TargetIncome;
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

            $platform_reinvest = Arr::get($event, 'result.buyAmount');
            $platform_referral = Arr::get($event, 'result.activateAmount');
            $commission_id_1 = Arr::get($event, 'result.fee');
            $receiver_platform_reinvest = $this->hexString2Base58(Arr::get($event, 'result.buyReceiver', ''));
            $receiver_platform_referral = $this->hexString2Base58(Arr::get($event, 'result.activateReceiver', ''));

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
        return compact(
            'platform_reinvest',
            'platform_referral',
            'receiver_platform_reinvest',
            'receiver_platform_referral',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex',
            'commission_id_1',
        );
    }

    /**
     * @param array $params
     */
    public
    function createNewResource(array $params): void
    {
        try {
            $receiver_platform_referral = Wallet::where('address', Arr::get($params, 'receiver_platform_referral'))->firstOrFail();

            $receiver_platform_referral->profit_referrals += Arr::get($params, 'platform_referral', 0);
            $receiver_platform_referral->amount_transfers = $receiver_platform_referral->profit_referrals + $receiver_platform_referral->profit_reinvest;
            $receiver_platform_referral->save();

            $receiver_platform_reinvest = Wallet::where('address', Arr::get($params, 'receiver_platform_reinvest'))->firstOrFail();

            $receiver_platform_reinvest->profit_reinvest += Arr::get($params, 'platform_reinvest', 0);
            $receiver_platform_reinvest->amount_transfers = $receiver_platform_reinvest->profit_referrals + $receiver_platform_reinvest->profit_reinvest;
            $receiver_platform_reinvest->save();


            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $receiver_platform_reinvest->id,
                'base58_id'     => Arr::get($params, 'base58_id'),
                'hex'           => Arr::get($params, 'hex'),
                'model_service' => TronService::class,
            ]);

            $transaction_event = TransactionEvent::create([
                "transaction_id"               => $transaction->id,
                "referrer_base58_address"      => Arr::get($params, 'receiver_platform_referral'),
                "contract_user_base58_address" => Arr::get($params, 'receiver_platform_reinvest'),
                'block_number'                 => Arr::get($params, 'block_number'),
                'block_timestamp'              => date('Y-m-d H:i:s', (int)Arr::get($params, 'block_timestamp') / 1000),
                'event_name'                   => Arr::get($params, 'event_name'),
            ]);
            FinancialTransaction::insert([
                [
                    'transaction_event_id' => $transaction_event->id,
                    'target_income_id'     => TargetIncome::where('name', 'referral')->firstOrFail()->id,
                    'amount'               => Arr::get($params, 'platform_referral', 0),
                    'wallet_id'            => $receiver_platform_referral->id,
                    'created_at'           => date('Y-m-d H:i:s', (int)Arr::get($params, 'block_timestamp') / 1000)
                ],
                [
                    'transaction_event_id' => $transaction_event->id,
                    'target_income_id'     => TargetIncome::where('name', 'account')->firstOrFail()->id,
                    'amount'               => Arr::get($params, 'platform_reinvest', 0),
                    'wallet_id'            => $receiver_platform_reinvest->id,
                    'created_at'           => date('Y-m-d H:i:s', (int)Arr::get($params, 'block_timestamp') / 1000)
                ]
            ]);
        } catch (\Throwable $exception) {
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }
    }
}
