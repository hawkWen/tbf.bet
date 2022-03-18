<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Helpers\FastbetBotApi;
use Illuminate\Console\Command;

class BotRestartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:restart';

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

        $brands = Brand::whereGameId(5)->get();

        foreach($brands as $brand) {

            if($brand->game_id == 5) {

                $fastbet_bot_api = new FastbetBotApi();

                $fastbet_bot_api->ip = $brand->server_api;

                $fastbet_bot_api->username = $brand->agent_username;

                $fastbet_bot_api->pass = $brand->agent_password;

                $fastbet_bot_api->token = 'yeahteam';

                // dd($fastbet_bot_api);

                $stop = $fastbet_bot_api->startStop('stop');

                if($stop['code'] == 200 || $stop['code'] == 500) {

                    $fastbet_bot_api->ip = $brand->server_api;

                    $fastbet_bot_api->username = $brand->agent_username;

                    $fastbet_bot_api->pass = $brand->agent_password;

                    $fastbet_bot_api->token = 'yeahteam';

                    $start = $fastbet_bot_api->startStop('start');

                }

            }
        }
    }
}
