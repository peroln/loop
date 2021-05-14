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
            $collect_event_array = $response->collect('data');

            $transaction_ids = $this->transactionRepository->retrieveHexIdRegistration();
            $registration_events = $collect_event_array
                ->whereNotIn("transaction_id", $transaction_ids)
                ->whereIn('event_name', ['Registration', 'AddedReferralLink'])
                ->all();

            $registration_events = collect($registration_events)->groupBy('transaction_id');
            $handled_event = collect($registration_events)->map(function ($item) {
                $item = collect($item);
                $event_registration = $item->where('event_name', 'Registration')->first();
                $event_ref_referral_link = $item->where('event_name', 'AddedReferralLink')->first();
                $referral_link = Arr::get($event_ref_referral_link, 'result.link');
                return array_merge($event_registration, ['referral_link' => $referral_link]);
            });

            if (count($handled_event)) {
                foreach ($handled_event as $event) {
                    /*if($event instanceof Collection){
                        $event = $event->toArray();
                    }*/
                    $array_dada_events = $this->cryptoService->extractDataFromRegisterTransaction($event);
                    $params = array_merge($array_dada_events, ['model_service' => $this->cryptoService::class]);
                    $tokens[] = $this->createWithWallet($params);
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


}
