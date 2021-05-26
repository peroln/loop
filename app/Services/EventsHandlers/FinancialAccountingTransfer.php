<?php


namespace App\Services\EventsHandlers;

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
            $receiver_platform_referral = $this->hexString2Base58(Arr::get($event, 'result.feeReceiver', ''));

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
        /*array:9 [
  "block_number" => 16318199
  "block_timestamp" => 1622018685000
  "caller_contract_address" => "TCybHQHSwaCNxXshMcwLfrTbXvcCKCoQF4"
  "contract_address" => "TCybHQHSwaCNxXshMcwLfrTbXvcCKCoQF4"
  "event_index" => 1
  "event_name" => "ReferralPaymentTransfer"
  "result" => array:12 [
    0 => "0x1ef65926237c26043f01e2a5accc9d0e3f1bf646"
    1 => "90000000"
    "activateReceiver" => "0x1ef65926237c26043f01e2a5accc9d0e3f1bf646"
    2 => "0x1ef65926237c26043f01e2a5accc9d0e3f1bf646"
    "buyAmount" => "90000000"
    3 => "90000000"
    4 => "0x1ef65926237c26043f01e2a5accc9d0e3f1bf646"
    5 => "20000000"
    "fee" => "20000000"
    "feeReceiver" => "0x1ef65926237c26043f01e2a5accc9d0e3f1bf646"
    "activateAmount" => "90000000"
    "buyReceiver" => "0x1ef65926237c26043f01e2a5accc9d0e3f1bf646"
  ]
  "result_type" => array:6 [
    "activateReceiver" => "address"
    "buyAmount" => "uint256"
    "fee" => "uint256"
    "feeReceiver" => "address"
    "activateAmount" => "uint256"
    "buyReceiver" => "address"
  ]
  "transaction_id" => "44e4c9150afea8ab9da7e59f712fe3a5f8061226869dc1b5a891a8cc8d8aa253"
]
*/
    }

    /**
     * @param array $params
     */
    public
    function createNewResource(array $params): void
    {
        try {
            $receiver_platform_referral= Wallet::where('address', Arr::get($params, 'receiver_platform_referral'))->firstOrFail();

            $receiver_platform_referral->profit_referrals += Arr::get($params, 'platform_referral', 0);
            $receiver_platform_referral->amount_transfers = $receiver_platform_referral->profit_referrals + $receiver_platform_referral->profit_reinvest;
            $receiver_platform_referral->save();

            $receiver_platform_reinvest= Wallet::where('address', Arr::get($params, 'receiver_platform_reinvest'))->firstOrFail();

            $receiver_platform_reinvest->profit_reinvest += Arr::get($params, 'platform_reinvest', 0);
            $receiver_platform_reinvest->amount_transfers = $receiver_platform_reinvest->profit_referrals + $receiver_platform_reinvest->profit_reinvest;
            $receiver_platform_reinvest->save();


            $transaction = Transaction::firstOrCreate([
                'wallet_id'     => $receiver_platform_reinvest->id,
                'base58_id'     => Arr::get($params, 'base58_id'),
                'hex'           => Arr::get($params, 'hex'),
                'model_service' => TronService::class
            ]);

            TransactionEvent::create([
                "transaction_id"               => $transaction->id,
                "referrer_base58_address"      => Arr::get($params, 'receiver_platform_referral'),
                "contract_user_base58_address" => Arr::get($params, 'receiver_platform_reinvest'),
                'block_number'                 => Arr::get($params, 'block_number'),
                'block_timestamp'              => Arr::get($params, 'block_timestamp'),
                'event_name'                   => Arr::get($params, 'event_name'),
            ]);
        } catch (\Throwable $exception) {
            Log::info(Arr::get($params, 'receiver_platform_referral'));
            Log::info(Arr::get($params, 'receiver_platform_reinvest'));
            Log::error(__FILE__ . '/' . $exception->getMessage());
        }
    }
    /*array:11 [
  "platform_reinvest" => "90000000"
  "platform_referral" => "90000000"
  "receiver_platform_reinvest" => "TCnvPPYna6YshPNUAopG88wsBkBunCh1rU"
  "receiver_platform_referral" => "TCnvPPYna6YshPNUAopG88wsBkBunCh1rU"
  "base58_id" => "W2AdGzSPsDE9vs9SgHVQpf7PYx8yYHNuZvH2qoPR2yzwAcsUF"
  "block_number" => 16318199
  "block_timestamp" => 1622018685000
  "event_name" => "ReferralPaymentTransfer"
  "hex" => "44e4c9150afea8ab9da7e59f712fe3a5f8061226869dc1b5a891a8cc8d8aa253"
  "commission_id_1" => "20000000"
  "model_service" => "App\Services\TronService"
]
*/
}
