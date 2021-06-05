<?php


namespace App\Services\EventsHandlers;


use App\Models\Wallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddReferralLinkHandler extends BaseEventsHandler
{
    const EVENT_NAME = 'AddedReferralLink';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {

        $referral_link = Arr::get($event, 'result.link');
        $referral_address = $this->hexString2Base58(Arr::get($event, 'result.referral'));
        $contract_user_base58_address = $referral_address;

        $hex = Arr::get($event, 'transaction_id', '');
        $block_number = Arr::get($event, 'block_number');
        $block_timestamp = date('Y-m-d H:i:s', (int)Arr::get($event, 'block_timestamp', microtime(true))/1000);
        $event_name = Arr::get($event, 'event_name');
        return compact(
            'referral_address',
            'referral_link',
            'hex',
            'block_number',
            'block_timestamp',
            'event_name',
            'contract_user_base58_address'
        );
    }

    /**
     * @param array $params
     */
    public function createNewResource(array $params): void
    {
        DB::beginTransaction();
        try {
            $wallet = Wallet::where('address', Arr::get($params, 'referral_address'))->firstOrFail();
            $wallet->referral_link = Arr::get($params, 'referral_link', '');
            $wallet->save();

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
