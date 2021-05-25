<?php


namespace App\Services\EventsHandlers;


class FinancialAccountingTransfer extends BaseEventsHandler
{
    const EVENT_NAME = 'ReferralPaymentTransfer';

    /**
     * @param array $event
     * @return bool|array
     */
    public function extractDataFromTransaction(array $event): bool|array
    {
        dd($event);
        // TODO: Implement extractDataFromTransaction() method.
        /*array:9 [
  "block_number" => 16279031
  "block_timestamp" => 1621899993000
  "caller_contract_address" => "TZ3KKqH7KA6iyQwQdUSZzUygGQUFEcHTAz"
  "contract_address" => "TZ3KKqH7KA6iyQwQdUSZzUygGQUFEcHTAz"
  "event_index" => 1
  "event_name" => "ReferralPaymentTransfer"
  "result" => array:8 [
    0 => "0xfed1478cddb4856a6d2f39c7b25de8277b55e422"
    1 => "225000000"
    "amount" => "225000000"
    2 => "0xfed1478cddb4856a6d2f39c7b25de8277b55e422"
    3 => "25000000"
    "fee" => "25000000"
    "to" => "0xfed1478cddb4856a6d2f39c7b25de8277b55e422"
    "feeReceiver" => "0xfed1478cddb4856a6d2f39c7b25de8277b55e422"
  ]
  "result_type" => array:4 [
    "amount" => "uint256"
    "fee" => "uint256"
    "to" => "address"
    "feeReceiver" => "address"
  ]
  "transaction_id" => "839ea82296223d0f5d10a7df7051ea96cccdf44c1a83a26cb2761228a0535126"
]
*/
    }

    /**
     * @param array $params
     */
    public function createNewResource(array $params): void
    {
        dd($params);
        // TODO: Implement createNewResource() method.
    }
}
