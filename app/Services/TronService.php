<?php


namespace App\Services;


use App\Models\Helpers\CryptoServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class TronService implements CryptoServiceInterface
{
    public string $contract_address;
    public string $api_host;

    public function __construct()
    {
        $this->contract_address = config('tron.contract_address');
        $this->api_host = config('tron.tron_host_api');
    }

    /**
     * @param string $transaction_id
     * @return bool|array
     */
    public function confirmRegistration(string $transaction_id): bool|array
    {
        $url = $this->formUrlRequest(\Str::of(__FUNCTION__)->snake('-'), compact('transaction_id' ));
        $response = Http::get($url);

        if ($response->successful() && count($response->json('data'))) {
            $collect_event_array = $response->collect('data');
            $registration_event = $collect_event_array->where('event_name', 'Registration')->firstWhere('contract_address', 'TS2rKkV7m5U27ASTurX9YHVXaeC8DXvudT');
            return $this->extractDataFromRegisterTransaction($registration_event);
        }
        return false;
    }

    /**
     * @param string $method_slug
     * @param array $params
     * @return string
     */
    private function formUrlRequest(string $method_slug, array $params): string
    {
        return match ($method_slug) {
            'confirm-registration' => $this->api_host . "/transactions/" . $params['transaction_id'] . "/events"
        };
    }

    /**
     * @param array $registration_event
     * @return bool|array
     */
    private function extractDataFromRegisterTransaction(array $registration_event): bool|array
    {
        $referrer_id = Arr::get($registration_event, 'result.referrerId');
        $contract_user_id = Arr::get($registration_event, 'result.userId');
        $referrer_cache_address = Arr::get($registration_event, 'result.referrer');
        $contract_user_cache_address = Arr::get($registration_event, 'result.user');
        $transaction_id = Arr::get($registration_event, 'transaction_id');

        return compact('referrer_id', 'contract_user_id', 'referrer_cache_address', 'contract_user_cache_address', 'transaction_id');
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

}
