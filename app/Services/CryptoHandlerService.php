<?php


namespace App\Services;


use App\Models\Helpers\CryptoServiceInterface;
use App\Models\TransactionEvent;
use App\Services\EventsHandlers\AddReferralLinkHandler;
use App\Services\EventsHandlers\FinancialAccountingTransfer;
use App\Services\EventsHandlers\OverflowPlatformEvent;
use App\Services\EventsHandlers\PlatformCreateEventHandler;
use App\Services\EventsHandlers\PlatformReactivationEvent;
use App\Services\EventsHandlers\PlatformSubscriberEventHandler;
use App\Services\EventsHandlers\WalletRegistrationEventHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CryptoHandlerService
{

    private array $handlers_classes = [
        WalletRegistrationEventHandler::class,
        AddReferralLinkHandler::class,
        PlatformCreateEventHandler::class,
        PlatformReactivationEvent::class,
        PlatformSubscriberEventHandler::class,
        OverflowPlatformEvent::class,
        FinancialAccountingTransfer::class,
    ];

    /**
     * CryptoHandlerService constructor.
     *
     * @param  CryptoServiceInterface  $cryptoService
     */
    public function __construct(
        public CryptoServiceInterface $cryptoService,
    )
    {
    }


    public function eventsHandler($event_name): void
    {
        $url        = $this->cryptoService->formUrlRequest(Str::of(__FUNCTION__)->snake('-'), null);
        $minTime    = null;
        $last_event = TransactionEvent::where('event_name', $event_name)->orderBy('block_timestamp', 'desc')->first();
        if ($last_event) {
            $minTime = strtotime($last_event->block_timestamp) * 1000;
        }
        $fingerprint = '';
        do {
            $response    = $this->receiveDataFromSite($url, $event_name, $fingerprint, $minTime);
            $fingerprint = Arr::get($response->json('meta'), 'fingerprint', '');
            if ($response->successful() && count($response->json('data'))) {
                $response = $response->collect('data');
                foreach ($this->handlers_classes as $handler_class) {
                    $handler = app()->make($handler_class);
                    $handler->handleResponse($response);
                }
            }
        } while ($fingerprint);
    }

    /**
     * @param  string  $url
     * @param  string  $event_name
     * @param  string  $fingerprint
     * @param  null    $minTime
     *
     * @return \Illuminate\Http\Client\Response
     */
    private function receiveDataFromSite(string $url, string $event_name, string $fingerprint = '', $minTime = null): \Illuminate\Http\Client\Response
    {
        return Http::get($url, [
            'limit'         => 200,
            'event_name'    => $event_name,
            'fingerprint'   => $fingerprint,
            'order_by'      => "timestamp,asc",
            'min_timestamp' => $minTime,
        ]);
    }
}

