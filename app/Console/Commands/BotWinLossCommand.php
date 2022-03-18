<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Helpers\Api;
use App\Models\Brand;
use App\Models\Promotion;
use App\Helpers\FastbetApi;
use App\Models\CustomerBet;
use App\Models\CustomerRefer;
use Illuminate\Console\Command;

class BotWinLossCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:win-loss {brand_sub_domain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Bot Win Loss';

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
        
        $this->winLoss($brand_sub_domain);
    }

    public function winLoss($brand_sub_domain) {

        $brand = Brand::whereSubdomain($brand_sub_domain)->first();

        $promotion = Promotion::whereBrandId($brand->id)->whereTypePromotion(5)->first();

        // dd($promotion,$brand);

        if($promotion->type_promotion_invite == 2) {

            $customer_refers = CustomerRefer::whereBrandId($brand->id)->whereStatus(0)->get();

            foreach($customer_refers as $customer_refer) {

                $api = new Api($brand);

                $data['username'] = $customer_refer->username;

                $data['refer_id'] = $customer_refer->refer_id;

                $api_win_loss = $api->winLossNew($data);

                if($api_win_loss['message'] === 'success') {

                    $win_loss_total = 0;

                    $bet_total = 0;

                    foreach($api_win_loss['result']['data'] as $game=>$winloss) {

                        if($game != 'GAME') {

                            $bet_total += $winloss['amount'];

                            $win_loss_total += $winloss['validAmount'];

                        }

                    }

                    $customer_bet = CustomerBet::whereCustomerReferId($customer_refer->id)->first();

                    if($customer_bet) {

                        $customer_bet->update([
                            'bet' => $bet_total,
                            'turn_over' => $win_loss_total,
                        ]);

                    } else {

                        CustomerBet::create([
                            'customer_refer_id' => $customer_refer->id,
                            'username' => $customer_refer->username,
                            'bet' => $bet_total,
                            'turn_over' => $win_loss_total, 
                            'start_date' => date('Y-m-d H:i:s'),
                            'end_date' => date('Y-m-d H:i:s'),
                            'status' => 0,
                            'status_invite' => 0,
                        ]);

                    }

                }

            }

        } 
        
        // else if($promotion->type_promotion_invite == 3) {

        //     $api = new Api($brand);
    
        //     $api_win_loss = $api->winLoss($data);

        // }
        
    }
    
}
