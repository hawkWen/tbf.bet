<?php

namespace App\Console\Commands;

use App\Helpers\Api;
use App\Models\Brand;
use App\Models\Promotion;
use Illuminate\Console\Command;

class BetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:bet {brand_sub_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bet Command';

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

        //Bot Win Loss Gclub;

        $brand_sub_domain = $this->argument('brand_sub_domain');

        $brand = Brand::whereSubdomain($brand_sub_domain)->first();

        $promotion = Promotion::whereTypePromotion(5)->whereStatus(0)->first();

        if($brand->game_id == 1) {

            $api = new Api($brand);
    
            $data['type'] = 'N';
    
            $data['start_date'] = date('Y-m-d');
    
            $data['end_date'] = date('Y-m-d');
    
            $api_win_loss = $api->winLoss($data);

        }
        
    }
}
