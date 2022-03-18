<?php

namespace App\Console\Commands;

use App\Helpers\Api;
use App\Models\Brand;
use Illuminate\Console\Command;

class BetTotalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:bet-total {brand_sub_domain}';

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

        $brand_sub_domain = $this->argument('brand_sub_domain');

        $brand = Brand::whereSubdomain($brand_sub_domain)->first();

        $api = new Api($brand);

        $data['type'] = 'Y';

        $data['start_date'] = date("Y-m-d", strtotime( '-1 days' ) );

        $data['end_date'] = date('Y-m-d');

        $api_win_loss = $api->winLossYesterday($data);

        

    }
}
