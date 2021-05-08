<?php


namespace App\Services;


use App\Models\Helpers\CryptoServiceInterface;
use App\Services\Blockchain\TronDecoder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TronService implements CryptoServiceInterface
{
    use  TronDecoder;

    public string $contract_address;
    public string $api_tron_host;

    public function __construct()
    {
        $this->contract_address = config('tron.contract_address');
        $this->api_tron_host = config('tron.tron_host_api');
    }

    /**
     * @param string $transaction_id
     * @return bool|array
     */
    public function confirmRegistration(string $transaction_id): bool|array
    {
        $url = $this->formUrlRequest(\Str::of(__FUNCTION__)->snake('-'), compact('transaction_id'));
        $response = Http::get($url);
        if ($response->successful() && count($response->json('data'))) {
            $collect_event_array = $response->collect('data');
            $registration_event = $collect_event_array->where('event_name', 'Registration')->firstWhere('contract_address', $this->contract_address);
            return $this->extractDataFromRegisterTransaction($registration_event);
        }
        return false;
    }

    /**
     * @param string $method_slug
     * @param array|null $params
     * @return string
     */
    public function formUrlRequest(string $method_slug, ?array $params): string
    {
        return match ($method_slug) {
            'confirm-registration' => $this->api_tron_host . "/transactions/" . $params['transaction_id'] . "/events",
            'extract-registered-wallets' => $this->api_tron_host . "/contracts/" . $this->contract_address . "/events"
        };
    }

    /**
     * @param array $registration_event
     * @return bool|array
     */
    public function extractDataFromRegisterTransaction(array $registration_event): bool|array
    {
        $referrer_id = Arr::get($registration_event, 'result.referrerId');
        $contract_user_id = Arr::get($registration_event, 'result.userId');
        $referrer_base58_address = $this->hexString2Base58(Arr::get($registration_event, 'result.referrer'));
        $contract_user_base58_address = $this->hexString2Base58(Arr::get($registration_event, 'result.user'));
        $base58_id = $this->hexString2Base58(Arr::get($registration_event, 'transaction_id'));
        $hex= Arr::get($registration_event, 'transaction_id');
        $block_number = Arr::get($registration_event, 'block_number');
        $block_timestamp = Arr::get($registration_event, 'block_timestamp');
        $event_name = Arr::get($registration_event, 'event_name');

        return compact(
            'referrer_id',
            'contract_user_id',
            'referrer_base58_address',
            'contract_user_base58_address',
            'base58_id',
            'block_number',
            'block_timestamp',
            'event_name',
            'hex'
        );
    }

    /**
     * @param $transaction_id
     * @return mixed
     */
    function receiveDataTransaction(string|int $transaction_id)
    {
        // TODO: Implement receiveDataTransaction() method.
    }

    /**
     * @return mixed
     */
    function receiveDataContractTransactions()
    {
        // TODO: Implement receiveDataContractTransactions() method.
    }

    /**
     * @return string
     */
    public function getImplementClass()
    {
        return self::class;
    }


}
