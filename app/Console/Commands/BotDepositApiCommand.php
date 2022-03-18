<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Helpers\Api;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\BotEvent;
use App\Models\Customer;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Helpers\FastbetApi;
use App\Models\WheelConfig;
use App\Models\PromotionCost;
use App\CustomerCreditHistory;
use App\Models\CustomerDeposit;
use Illuminate\Console\Command;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccountTransaction;

class BotDepositApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:deposit-api {brand_sub_domain}';

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

        $brand = Brand::whereSubdomain($brand_sub_domain)->whereStatusBotDeposit(1)->first();

        if($brand) {

            $a = 0;

            while ($a <= 60) {

                $this->deposit($brand);
                
                $a += 20;

                sleep(20);

            }


        }
            
    }

    public function deposit($brand) {

        $seconds = (int)date('s');
            
        $bank_account_transaction = BankAccountTransaction::whereBrandId($brand->id)
            ->where('code','=','X1')
            ->whereIn('status',[0])
            ->orderBy('created_at','desc')->first();

        if($bank_account_transaction) {

            //Check Bank Account Unique
            $bank_account_unique = BankAccountTransaction::where('transfer_at','=',$bank_account_transaction->transfer_at)
                ->whereBankAccount($bank_account_transaction->bank_account)
                ->where('status','!=',0)
                ->get();

            if($bank_account_unique->count() > 0) {

                $bank_account_transaction->update([
                    'status' => 3,
                    'log' => 'TRANSACTION UNIQUE'
                ]);

                return abort(500, 'Transaction Unqiue');

            }

            $bank_account_transaction->update([
                'status' => 2,
                'log' => 'WAIT CUSTOMER CHECK',
            ]);
            
            //Find Customer SCB
            if($bank_account_transaction->bank_id == 1) {

                $customer = Customer::whereBrandId($brand->id)
                    ->whereBankAccountScb($bank_account_transaction->bank_account)
                    ->where('status_manual','=',0)
                    ->whereCodeBank($bank_account_transaction->code_bank)->first();

                $customer_count = Customer::whereBrandId($brand->id)
                    ->whereBankAccountScb($bank_account_transaction->bank_account)
                    ->whereCodeBank($bank_account_transaction->code_bank)
                    ->get();

                if($customer_count->count() > 1) {

                    $bank_account_transaction->update([
                        'status' => 8,
                        'log' => 'CUSTOMER SCB UNIQUE'
                    ]);

                    return abort(500, 'CUSTOMER SCB UNIQUE');

                }

            //Find Customer TRUEMONEY
            } else if ($bank_account_transaction->bank_id == 0) {

                $telephone = $bank_account_transaction->bank_account;

                $t1 = substr($telephone,0,3);
            
                $t2 = substr($telephone,3);
            
                $telephone = $t1.'-'.$t2;
                //truemoney
                $customer = Customer::whereBrandId($brand->id)->whereTelephone($telephone)->where('status_manual','=',0)->first();

                if(!$customer) {

                    $bank_account_transaction->update([
                        'status' => 8,
                        'log' => 'CUSTOMER TRUEMONY UNIQUE'
                    ]);

                    return abort(500, 'CUSTOMER TRUEMONEY UNIQUE');

                }

            }

            // CUSTOMER NOT FOUND
            if(!$customer) {

                $bank_account_transaction->update([
                    'status' => 4,
                    'log' => 'CUSTOMER NOT FOUND'
                ]);

                return abort(500, 'CUSTOMER NOT FOUND');

            }

            $bank_account_transaction->update([
                'status' => 2,
                'log' => 'WAIT DEPOSIT MANUAL CHECK',
            ]);

            $bank_account_transaction_transfer_at = Carbon::createFromFormat('Y-m-d H:i:s',$bank_account_transaction->transfer_at)->format('Y-m-d H:i:00');

            $customer_deposit_manual = CustomerDeposit::whereCustomerId($customer->id)->whereTransferAt($bank_account_transaction_transfer_at)->whereTypeDeposit(2)->first();
            
            // Find Manual Customer
            if($customer_deposit_manual) {

                $bank_account_transaction->update([
                    'status' => 5,
                    'log' => 'CUSTOMER MANUAL ALREADY'
                ]);

                return abort(500, 'CUSTOMER MANUAL ALREADY');

            }

            $bank_account_transaction->update([
                'status' => 2,
                'log' => 'WAIT FOR PROMOTION CHECK',
            ]);

            // CHECK PROMOTION
            $promotion_cost = PromotionCost::whereBrandId($brand->id)->where('promotion_id','!=',0)->whereCustomerId($customer->id)->whereStatus(0)->first();

            if($promotion_cost) {

                if($promotion_cost->promotion->type_turn_over == 1 && $promotion_cost->promotion->type_promotion_cost == 1) {

                    $total_turn_over = ($promotion_cost->amount + $promotion_cost->bonus) * $promotion_cost->promotion->turn_over;

                    $api = new Api($brand);

                    $data['username'] = $customer->username;

                    $data['agent_order'] = $customer->agent_order;

                    $api_credit = $api->credit($data);

                    if($api_credit['status'] === true) {

                        $credit = $api_credit['data']['credit'];

                    } else {

                        $credit = $customer->credit;

                    }

                    if($customer->credit < $total_turn_over && $credit > $promotion_cost->promotion->min_break_promotion) {

                        $bank_account_transaction->update([
                            'status' => 6,
                            'log' => 'PROMOTION EXIT',
                        ]);

                        return abort(500, 'PROMOTION EXIT');

                    } else {

                        $promotion_cost->update([
                            'status' => 1,
                        ]);

                    }

                } 
                
            }

            $bank_account_transaction->update([
                'status' => 2,
                'log' => 'WAIT FOR CONNECT API',
            ]);

            $this->depositApi($brand, $customer,$bank_account_transaction);

        }

    }

    public function depositApi($brand, $customer, $bank_account_transaction) {

        $total_amount = $bank_account_transaction->amount;

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['amount'] = $bank_account_transaction->amount;

        $data['customer_id'] = $customer->id;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }


        $api_deposit = $api->deposit($data);

        if($brand->game_id == 1) {

            //GCLUB CHECK USER ONLINE
            if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                //customer online
                $bank_account_transaction->update([
                    'status' => 9,
                    'log' => 'CUSTOMER ONLINE',
                ]);
    
                return abort(500, 'CUSTOMER ONLINE');

            } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {

                //API ERROR
                $bank_account_transaction->update([
                    'status' => 0,
                    'log' => 'API ERROR TRY AGAIN',
                ]);
    
                return abort(500, 'API ERROR TRY AGAIN');

            }

        } else {

            if($api_deposit['status'] == false) {

                //API ERROR TRY AGAIN
                $bank_account_transaction->update([
                    'status' => 0,
                    'log' => 'API ERROR TRY AGAIN',
                ]);
    
                return abort(500, 'API ERROR TRY AGAIN');
    
            }

            if(isset($api_deposit['data']['ref'])) {

                $refer_id = $api_deposit['data']['ref'];

            } else {

                $refer_id = 0;

            }

        }

        $bank_account_transaction->update([
            'status' => 2,
            'log' => 'API DEPOSIT SUCCESS',
        ]);

        DB::beginTransaction();

        //Create Customer Deposit
        $customer_deposit = CustomerDeposit::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'game_id' => $brand->game_id,
            'promotion_id' => 0,
            'bank_account_id' => $bank_account_transaction->bank_account_id,
            'name' => $customer->name,
            'username' => $customer->username,
            'amount' => $bank_account_transaction->amount,
            'bonus' => 0,
            'type_deposit' => 2,
            'status' => 1,
        ]);

        //Bank Account Transaction Update
        $bank_account_transaction->update([
            'status' => 1,
            'customer_deposit_id' => $customer_deposit->id, 
        ]);

        //Create Credit History
        CustomerCreditHistory::create([
            'brand_id' => $brand->id,
            'customer_id' => $customer->id,
            'customer_deposit_id' => $customer_deposit->id,
            'amount_before' => $customer->credit,
            'amount' => $total_amount,
            'amount_after' => $customer->credit + $total_amount,
            'type' => 1,
        ]);

        //wheel update score
        $wheel_config = WheelConfig::whereBrandId($brand->id)->first();

        if($wheel_config) {

            $wheel_score_total = $customer->wheel_score + $bank_account_transaction->amount;

            if($wheel_score_total >= $wheel_config->amount_condition) {

                $wheel_amount_total = $customer->wheel_amount + 1;
        
                $customer->update([
                    'wheel_score' => 0,
                    'wheel_amount' => $wheel_amount_total
                ]);

            } else {
        
                $customer->update([
                    'wheel_score' => $customer->wheel_score + $bank_account_transaction->amount,
                ]);

            }

        }

        // Line Notify
        $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

        // if($brand->line_channel_secret) {

        //     $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

        //     $line_api = new LineApi();

        //     $line_api->token = $brand->line_token;

        //     $line_api->channel_secret = $brand->line_channel_secret;

        //     $push = $line_api->pushMessage($customer->line_user_id, $message);

        // }

        if($brand->line_notify_token) {

            $message_line_notify = "เติมเงิน\n";
            $message_line_notify .= "------------------- \n";
            $message_line_notify .= "ลูกค้า : ". $customer->name."\n";
            $message_line_notify .= "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";
            $message_line_notify .= "------------------- \n";
            $message_line_notify .= "ประวัติการเติมเงิน: https://agent.".  env('APP_NAME') .".".env('APP_DOMAIN') ."/deposit/history";
            $message_line_notify = str_replace('%','',$message_line_notify);

            $msg = trim($message_line_notify);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://notify-api.line.me/api/notify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "message=$msg",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$brand->line_notify_token,
                    "Content-Type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

        }

        DB::commit();

    }

}
