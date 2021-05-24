<?php

namespace App\Console\Commands;

use App\Events\PingEvent;
use App\Events\ReactivationPlatform;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     *
     */
    public function handle()
    {
        broadcast(new PingEvent());
       broadcast(new ReactivationPlatform());
       $this->info('Ping Command');
    }
}
