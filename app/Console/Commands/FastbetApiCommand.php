<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Helpers\FastbetBotApi;
use Illuminate\Console\Command;

class FastbetApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fastbet:api {order}';

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
        $command = $this->argument('order');

        $fastbet_brands = Brand::whereGameId(5)->whereStatusBot(1)->get();

        if($command == 'restart') {

            foreach($fastbet_brands as $fastbet_brand) {

                $fastbet_bot_api = new FastbetBotApi();

                $fastbet_bot_api->ip = $fastbet_brand->server_api;

                $fastbet_bot_api->username = $fastbet_brand->agent_username;

                $fastbet_bot_api->pass = $fastbet_brand->agent_password;

                $fastbet_bot_api->token = 'yeahteam';
                
                $stop = $fastbet_bot_api->startStop('stop');

                if($stop['code'] == 200 || $stop['code'] == 500) {

                    $fastbet_bot_api->ip = $fastbet_brand->server_api;
        
                    $fastbet_bot_api->username = $fastbet_brand->agent_username;
        
                    $fastbet_bot_api->pass = $fastbet_brand->agent_password;
        
                    $fastbet_bot_api->token = 'yeahteam';

                    $start = $fastbet_bot_api->startStop('start');

                }

            }

        }
        
    }
}
