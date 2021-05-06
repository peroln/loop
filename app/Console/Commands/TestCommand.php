<?php

namespace App\Console\Commands;

use App\Services\Blockchain\TronDecoder;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    use TronDecoder;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:name';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dd($this->hexString2Base58('0x669d140f1eb8049bd3ad85f0152643333b491b14'));
        return 0;
    }
}
