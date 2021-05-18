<?php


namespace App\Services;


use App\Models\Helpers\CryptoServiceInterface;
use App\Repositories\TransactionEventRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
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
     */
    public function __construct(
        public CryptoServiceInterface $cryptoService,
        public UserRepository $userRepository,
        private TransactionRepository $transactionRepository,
        private TransactionEventRepository $transactionEventRepository,
        private WalletRepository $walletRepository
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function extractRegisteredWallets(): void
    {
        $url = $this->cryptoService->formUrlRequest(Str::of(__FUNCTION__)->snake('-'), null);
        $response = Http::get($url);
        if ($response->successful() && count($response->json('data'))) {
            $models_param_array = $this->handlingData($response);
            if (count($models_param_array)) {
                foreach ($models_param_array as $params) {
                    $this->createWithWallet($params);
                }
            }
        }
    }

    /**
     * @param array $params
     * @return string
     * @throws Throwable
     */
    public function createWithWallet(array $params): string
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
            throw $e;
        }
        return auth()->fromUser($wallet);
    }

    /**
     * @param $response
     * @return array
     */
    private function handlingData($response): array
    {
        $params = [];
        $collect_event_array = $response->collect('data');
        $handled_event = $this->handlingEvents($collect_event_array);
        if ($handled_event instanceof Collection && $handled_event->count()) {
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

    /**
     * @param $collect_event_array
     * @return Collection
     */
    private function handlingEvents($collect_event_array): Collection
    {
        $transaction_ids = $this->transactionRepository->retrieveHexIdRegistration();
        $registration_events = $collect_event_array
            ->whereNotIn("transaction_id", $transaction_ids)
            ->whereIn('event_name', $this->cryptoService::EVENTS_TRON)
            ->all();
//TODO Here should be handling event ~ "upgrade" (create new platform)
        $registration_events = collect($registration_events)->groupBy('transaction_id');
        return collect($registration_events)->map(function ($item) {
            $item = collect($item);
            if ($item->count() === count($this->cryptoService::EVENTS_TRON)) {
                $event_registration = $item->where('event_name', 'Registration')->first();
                $event_ref_referral_link = $item->where('event_name', 'AddedReferralLink')->first();
                $referral_link = Arr::get($event_ref_referral_link, 'result.link');
                return array_merge($event_registration, ['referral_link' => $referral_link]);
            }
        });
    }
}
