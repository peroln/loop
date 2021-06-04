<?php


namespace App\Services;


use App\Models\Helpers\CryptoServiceInterface;
use App\Services\EventsHandlers\FinancialAccountingTransfer;
use App\Services\EventsHandlers\OverflowPlatformEvent;
use App\Services\EventsHandlers\PlatformCreateEventHandler;
use App\Services\EventsHandlers\PlatformReactivationEvent;
use App\Services\EventsHandlers\PlatformSubscriberEventHandler;
use App\Services\EventsHandlers\WalletRegistrationEventHandler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CryptoHandlerService
{

    private array $handlers_classes = [
        WalletRegistrationEventHandler::class,
        PlatformCreateEventHandler::class,
        PlatformReactivationEvent::class,
        PlatformSubscriberEventHandler::class,
        OverflowPlatformEvent::class,
        FinancialAccountingTransfer::class
    ];

    /**
     * CryptoHandlerService constructor.
     * @param CryptoServiceInterface $cryptoService
     */
    public function __construct(
        public CryptoServiceInterface $cryptoService,
    )
    {
    }


    public function eventsHandler($event_name): void
    {
        $url = $this->cryptoService->formUrlRequest(Str::of(__FUNCTION__)->snake('-'), null);
        $response = Http::get($url, ['limit' => 200, 'event_name' => $event_name]);
        if ($response->successful() && count($response->json('data'))) {
            $response = $response->collect('data')->reverse();
            foreach ($this->handlers_classes as $handler_class) {
                $handler = app()->make($handler_class);
                $handler->handleResponse($response);
            }
        }
    }
}
