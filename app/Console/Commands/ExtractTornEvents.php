<?php

namespace App\Console\Commands;

use App\Services\CryptoHandlerService;
use App\Services\EventsHandlers\AddReferralLinkHandler;
use App\Services\EventsHandlers\FinancialAccountingTransfer;
use App\Services\EventsHandlers\OverflowPlatformEvent;
use App\Services\EventsHandlers\PlatformCreateEventHandler;
use App\Services\EventsHandlers\PlatformReactivationEvent;
use App\Services\EventsHandlers\PlatformSubscriberEventHandler;
use App\Services\EventsHandlers\WalletRegistrationEventHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExtractTornEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'torn-events:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command send request to server and extract registered wallets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     *
     */
    public function handle(CryptoHandlerService $service)
    {
        $arr_events_name = [
            WalletRegistrationEventHandler::EVENT_NAME,
            AddReferralLinkHandler::EVENT_NAME,
            PlatformCreateEventHandler::EVENT_NAME,
            PlatformReactivationEvent::EVENT_NAME,
            PlatformSubscriberEventHandler::EVENT_NAME,
            OverflowPlatformEvent::EVENT_NAME,
            FinancialAccountingTransfer::EVENT_NAME
        ];
        try {
            foreach ($arr_events_name as $event_name)
                $service->eventsHandler($event_name);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }
}
