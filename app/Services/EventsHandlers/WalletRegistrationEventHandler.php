<?php


namespace App\Services\EventsHandlers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletRegistrationEventHandler extends BaseEventsHandler
{
    const EVENT_NAME = 'Registration';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {
        try {
            $referrer_id = Arr::get($event, 'result.referrerId');
            $contract_user_id = Arr::get($event, 'result.userId');
            $referrer_base58_address = $this->hexString2Base58(Arr::get($event, 'result.referrer'));

            $contract_user_base58_address = $this->hexString2Base58(Arr::get($event, 'result.user'));
            $base58_id = $this->hexString2Base58(Arr::get($event, 'transaction_id'));
            $amount_transfers = 0;

            $hex = Arr::get($event, 'transaction_id', '');
            $call_value = Arr::get($event, 'result.amount');
            $block_number = Arr::get($event, 'block_number');
            $block_timestamp = date('Y-m-d H:i:s', (int)Arr::get($event, 'block_timestamp', microtime(true))/1000);
            $event_name = Arr::get($event, 'event_name');
//            $referral_link = Arr::get($event, 'referral_link');

        } catch (\Throwable $e) {
            Log::error(__FILE__ . ' ' . $e->getMessage());
        }
        return compact(
            'referrer_id',
            'contract_user_id',
            'referrer_base58_address',
            'contract_user_base58_address',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex',
            'call_value',
            'amount_transfers',
//            'referral_link'
        );
    }

    /**
     * @param array $params
     */
    public function createNewResource(array $params): void
    {
        DB::beginTransaction();
        try {
            $user_data_params = $this->userRepository->createUserDataParams($params);
            $user = $this->userRepository->create($user_data_params);

            $wallet_data_params = $this->walletRepository->createWalletDataParams($params);
            $wallet = $user->wallet()->create($wallet_data_params);

            $transaction_data_params = $this->transactionRepository->createTransactionDataParams($params);
            $transaction = $wallet->transactions()->create($transaction_data_params);

            $transaction_events_data_params = $this->transactionEventRepository->createTransactionEventDataParams($params);
            $transaction->transactionEvents()->create($transaction_events_data_params);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error(__FILE__ . '/ ' . $e->getMessage());
        }
    }
}
