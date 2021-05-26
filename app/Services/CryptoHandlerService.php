<?php


namespace App\Services;


use App\Models\Helpers\CryptoServiceInterface;
use App\Models\Wallet;
use App\Repositories\TransactionEventRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\EventsHandlers\FinancialAccountingTransfer;
use App\Services\EventsHandlers\OverflowPlatformEvent;
use App\Services\EventsHandlers\PlatformCreateEventHandler;
use App\Services\EventsHandlers\PlatformReactivationEvent;
use App\Services\EventsHandlers\PlatformSubscriberEventHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;


class CryptoHandlerService
{


    /**
     * CryptoHandlerService constructor.
     * @param CryptoServiceInterface $cryptoService
     * @param UserRepository $userRepository
     * @param TransactionRepository $transactionRepository
     * @param TransactionEventRepository $transactionEventRepository
     * @param WalletRepository $walletRepository
     * @param PlatformHandlerService $platformHandlerService
     */
    public function __construct(
        public CryptoServiceInterface $cryptoService,
        public UserRepository $userRepository,
        private TransactionRepository $transactionRepository,
        private TransactionEventRepository $transactionEventRepository,
        private WalletRepository $walletRepository,
        private PlatformHandlerService $platformHandlerService
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function fixRegisteredWallets(Collection $response): void
    {
        $models_param_array = $this->handlingData($response, 'Registration');
        if (count($models_param_array)) {
            foreach ($models_param_array as $params) {
                $this->createWithWallet($params);
            }
        }

    }

    public function fixReactivation(Collection $response): void
    {
        $models_param_array = $this->handlingData($response, 'Reactivation');
        if (count($models_param_array)) {
            foreach ($models_param_array as $params) {
                $this->reactivationPlatform($params);
            }
        }

    }

    public function eventsHandler(): void
    {
        $url = $this->cryptoService->formUrlRequest(Str::of(__FUNCTION__)->snake('-'), null);
        $response = Http::get($url, ['limit' => 200]);
        if ($response->successful() && count($response->json('data'))) {
            $response = $response->collect('data');
//       TODO     change to class
            $this->fixRegisteredWallets($response);

            $create_platforms = app()->make(PlatformCreateEventHandler::class);
            $create_platforms->handleResponse($response);

            $create_subscribers = app()->make(PlatformSubscriberEventHandler::class);
            $create_subscribers->handleResponse($response);

            $create_reinvest = app()->make(PlatformReactivationEvent::class);
            $create_reinvest->handleResponse($response);

            $overflow_event = app()->make(OverflowPlatformEvent::class);
            $overflow_event->handleResponse($response);

            $financial_event = app()->make(FinancialAccountingTransfer::class);
            $financial_event->handleResponse($response);

        }
    }

    /**
     * @param array $params
     * @throws Throwable
     */
    public function createWithWallet(array $params): void
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
        } catch (Throwable $e) {
            DB::rollBack();
           Log::error(__FILE__ . '/ '. $e->getMessage());
        }
    }

    /**
     * @param Collection $response
     * @param string $event_name
     * @return array
     */
    private function handlingData(Collection $response, string $event_name): array
    {
        return match ($event_name) {
            'Registration' => $this->handlingRegistrationEvents($response),
            'Reactivation' => $this->handlingReactivationEvents($response)
        };

    }

    /**
     * @param Collection $collect_event_array
     * @return array
     */
    private function handlingRegistrationEvents(Collection $collect_event_array): array
    {
        $registration_events = $this->extractEvents($collect_event_array, $this->cryptoService::EVENTS_TRON);
        $registration_events = collect($registration_events)->groupBy('transaction_id');
        $handled_event = collect($registration_events)->map(function ($item) {
            $item = collect($item);
            if ($item->count() === count($this->cryptoService::EVENTS_TRON)) {
                $event_registration = $item->where('event_name', 'Registration')->first();
                $event_ref_referral_link = $item->where('event_name', 'AddedReferralLink')->first();
                $referral_link = Arr::get($event_ref_referral_link, 'result.link');
                return array_merge($event_registration, ['referral_link' => $referral_link]);
            }
        });
        $params = [];
        if ($handled_event->count()) {
            try {
                foreach ($handled_event as $event) {
                    if (is_array($event) && count($event)) {
                        $array_dada_events = $this->cryptoService->extractDataFromRegisterTransaction($event);
                        if (is_array($array_dada_events) && count($array_dada_events)) {
                            $params[] = array_merge($array_dada_events, ['model_service' => $this->cryptoService::class]);
                        };
                    }
                }
            } catch (\Throwable $e) {
                Log::debug($e->getMessage());
            }

        }
        return $params;
    }

    private function handlingReactivationEvents($collect_event_array): array
    {
        $params = [];
        $reactivation_events = $this->extractEvents($collect_event_array, [$this->cryptoService::EVENT_REACTIVATION]);
        if (count($reactivation_events)) {
            try {
                foreach ($reactivation_events as $reactivation_event) {
                    $params[] = $this->cryptoService->extractDataFromReactivationTransaction($reactivation_event);
                }
            } catch (Throwable $e) {
                Log::debug($e->getMessage());
            }
        };
        return $params;
    }

    /**
     * @param Collection $collect_event_array
     * @param array $arr_names
     * @return array
     */
    private function extractEvents(Collection $collect_event_array, array $arr_names): array
    {
        $except_transaction_ids = $this->transactionRepository->retrieveHexIdRegistration($arr_names);
        return $collect_event_array
            ->whereNotIn("transaction_id", $except_transaction_ids)
            ->whereIn('event_name', $arr_names)
            ->all();
    }

    private function reactivationPlatform(array $params)
    {
        $contract_user_address = Arr::get($params, 'contract_user_base58_address');
        $platform_level_id = Arr::get($params, 'platform_level_id');

        $wallet = Wallet::where('address', $contract_user_address)->firstOrFail();
        $this->platformHandlerService->reactivationPlatform($wallet->id, $platform_level_id);

        $transaction_data_params = $this->transactionRepository->createTransactionDataParams($params);
        $transaction = $wallet->transactions()->create($transaction_data_params);

        $transaction_events_data_params = $this->transactionEventRepository->createTransactionEventDataParams($params);
        $transaction->transactionEvents()->create($transaction_events_data_params);
    }
}
