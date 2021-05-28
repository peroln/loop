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
use App\Services\EventsHandlers\WalletRegistrationEventHandler;
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
     */
    public function __construct(
        public CryptoServiceInterface $cryptoService,
    )
    {
    }


    public function eventsHandler(): void
    {
        $url = $this->cryptoService->formUrlRequest(Str::of(__FUNCTION__)->snake('-'), null);
        $response = Http::get($url, ['limit' => 200]);
        if ($response->successful() && count($response->json('data'))) {
            $response = $response->collect('data');

            $create_wallets = app()->make(WalletRegistrationEventHandler::class);
            $create_wallets->handleResponse($response);

            $create_platforms = app()->make(PlatformCreateEventHandler::class);
            $create_platforms->handleResponse($response);

            $create_reinvest = app()->make(PlatformReactivationEvent::class);
            $create_reinvest->handleResponse($response);

            $create_subscribers = app()->make(PlatformSubscriberEventHandler::class);
            $create_subscribers->handleResponse($response);

            $overflow_event = app()->make(OverflowPlatformEvent::class);
            $overflow_event->handleResponse($response);

            $financial_event = app()->make(FinancialAccountingTransfer::class);
            $financial_event->handleResponse($response);

        }
    }
}
