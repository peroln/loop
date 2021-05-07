<?php

namespace App\Console\Commands;

use App\Models\Helpers\CryptoServiceInterface;
use App\Services\CryptoHandlerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExtractRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registration:extract';

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
      /*  try{
            $service->extractRegisteredWallets();
        }catch(\Throwable $e){
            Log::error($e->getMessage());
        }*/

    }
}
