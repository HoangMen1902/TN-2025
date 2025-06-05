<?php

namespace App\Console\Commands;

use App\Services\ViettelPostService;
use Illuminate\Console\Command;

class GetTokenViettelPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-token-viettel-post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viettelpost = new ViettelPostService;
        $viettelpost->updateToken();
    }
}
