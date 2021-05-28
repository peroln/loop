<?php


namespace App\Services\EventsHandlers;


use App\Models\Helpers\CryptoServiceInterface;
use App\Repositories\TransactionEventRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\Blockchain\TronDecoder;
use App\Services\PlatformHandlerService;
use App\Services\TronService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class  BaseEventsHandler
{
    use  TronDecoder;

    public function __construct(
        public TransactionRepository $transactionRepository,
        public CryptoServiceInterface $cryptoService,
        public UserRepository $userRepository,
        public TransactionEventRepository $transactionEventRepository,
        public WalletRepository $walletRepository,
        public PlatformHandlerService $platformHandlerService
    )
    {
    }

    /**
     * @param Collection $collect_event_array
     * @param array $arr_names
     * @return array
     */
    public function extractEvents(Collection $collect_event_array, array|string $arr_names): array
    {
        $except_transaction_ids = $this->transactionRepository->retrieveHexIdRegistration($arr_names);
        return $collect_event_array
            ->whereNotIn("transaction_id", $except_transaction_ids)
            ->whereIn('event_name', $arr_names)
            ->all();
    }

    /**
     * @param array $event
     * @return bool|array
     */
    abstract public function extractDataFromTransaction(array $event): bool|array;

    /**
     * @param Collection $response
     * @return array
     */
    public function extractEventData(Collection $response): array
    {
        $events = $this->extractEvents($response, static::EVENT_NAME);
        $events = collect($events);
        $params = [];
        if (is_array(static::EVENT_NAME)) {
            $events = $this->handleMultiEvents($events);
        }
        if ($events->count()) {
            try {
                foreach ($events as $event) {
                    if (is_array($event) && count($event)) {
                        $array_dada_events = $this->extractDataFromTransaction($event);
                        if (is_array($array_dada_events) && count($array_dada_events)) {
                            $params[] = array_merge($array_dada_events, ['model_service' => TronService::class]);
                        };
                    }
                }
            } catch (\Throwable $e) {
                Log::debug(__FILE__ . ' ' . $e->getMessage());
            }

        }
        return $params;
    }

    /**
     * @param Collection $events
     * @return Collection
     */
    private function handleMultiEvents(Collection $events): Collection
    {
        $registration_events = $events->groupBy('transaction_id');
        return collect($registration_events)->map(function ($item) {
            $item = collect($item);
            if ($item->count() === count(static::EVENT_NAME)) {
                $event_registration = $item->where('event_name', 'Registration')->first();
                $event_ref_referral_link = $item->where('event_name', 'AddedReferralLink')->first();
                $referral_link = Arr::get($event_ref_referral_link, 'result.link');
                return array_merge($event_registration, ['referral_link' => $referral_link]);
            }
        });
    }

    /**
     * @param Collection $response
     */
    public function handleResponse(Collection $response): void
    {
        $params = $this->extractEventData($response);
        if (count($params)) {
            foreach ($params as $param) {
                $this->createNewResource($param);
            }
        }

    }

    /**
     * @param array $params
     */
    abstract public function createNewResource(array $params): void;
}
