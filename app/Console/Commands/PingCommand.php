<?php

namespace App\Console\Commands;

use App\Events\Overflow as OverflowEvent;
use App\Events\PingEvent;
use App\Events\ReactivationPlatform;
use App\Models\Service\Overflow;
use App\Models\Service\Platform;
use Illuminate\Console\Command;


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
        broadcast(new OverflowEvent(Overflow::first()));
        broadcast(new ReactivationPlatform(Platform::first()));
    }
}
