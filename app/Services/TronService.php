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
     * @param string $method_slug
     * @param array|null $params
     * @return string
     */
    public function formUrlRequest(string $method_slug, ?array $params): string
    {
        return match ($method_slug) {
            'confirm-registration' => $this->api_tron_host . "/v1/transactions/" . $params['transaction_id'] . "/events",
            'events-handler' => $this->api_tron_host . "/v1/contracts/" . $this->contract_address . "/events",
            'receive-transaction-call-value' => $this->api_tron_host . '/walletsolidity/gettransactionbyid',
            'get-account-balance' => $this->api_tron_host . '/v1/accounts/' . $params['address'],
        };
    }


    /**
     * @param string $address
     * @return int
     */
    public function getAccountBalance(string $address): int
    {
        $url = $this->formUrlRequest(\Str::of(__FUNCTION__)->snake('-'), compact('address'));
        $response = Http::get($url);
        if ($response->successful() && count($response->json('data'))) {
            $data = $response->json('data');
            if (count($data)) {
                return Arr::get($data[0], 'balance');
            }
        }
        return 0;
    }


    /**
     * @return string
     */
    public function getImplementClass()
    {
        return self::class;
    }
}
