<?php

namespace App\Console\Commands;

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
use App\Models\PromotionCost;
use App\CustomerCreditHistory;
use App\Models\CustomerDeposit;
use Illuminate\Console\Command;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccountTransaction;

class BotDepositCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:deposit {brand_sub_domain}';

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
                
                $a += 10;

                sleep(10);

            }

            $this->depositPromotion($brand);

        }
            
    }

    public function deposit($brand) {

        $seconds = (int)date('s');

        if($seconds > 9) {

            $bank_account_transaction = BankAccountTransaction::whereBrandId($brand->id)->whereStatus(0)->orderBy('transfer_at','desc')->first();

            if($bank_account_transaction) {

                $bank_account_unique = BankAccountTransaction::where('transfer_at','=',$bank_account_transaction->transfer_at)
                    ->whereBankAccount($bank_account_transaction->bank_account)
                    ->where('status','!=',0)
                    ->get();

                if($bank_account_transaction && $bank_account_unique->count() == 0) {

                    if($bank_account_transaction->bank_id == 1) {
        
                        //SCB
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
                                'status' => 8
                            ]);

                            return abort(500, 'CUSTOMER UNIQUE');

                        }

                    } else if ($bank_account_transaction->bank_id == 4) {

                        //Kbank
                        $customer = Customer::whereBrandId($brand->id)->whereBankAccountScb($bank_account_transaction->bank_account)->where('status_manual','=',0)->first();

                    } else if ($bank_account_transaction->bank_id == 5) {

                        //Krungsri
                        $customer = Customer::whereBrandId($brand->id)->whereBankAccountScb($bank_account_transaction->bank_account)->where('status_manual','=',0)->first();

                    } else if ($bank_account_transaction->bank_id == 0) {

                        $telephone = $bank_account_transaction->bank_account;

                        $t1 = substr($telephone,0,3);
                    
                        $t2 = substr($telephone,3);
                    
                        $telephone = $t1.'-'.$t2;
                        //truemoney
                        $customer = Customer::whereBrandId($brand->id)->whereTelephone($telephone)->where('status_manual','=',0)->first();

                    }

                    if($customer) {
                        
                        $promotion_cost = PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

                        if($promotion_cost) {

                            if($promotion_cost->promotion->type_turn_over == 1 && $promotion_cost->promotion->type_promotion_cost == 1) {

                                $total_turn_over = ($promotion_cost->amount + $promotion_cost->bonus) * $promotion_cost->promotion->turn_over;

                                if($customer->credit < $total_turn_over) {

                                    $bank_account_transaction->update([
                                        'customer_id' => $customer->id,
                                        'status' => 7,
                                    ]);

                                    return abort(500, 'PROMOTION EXIT');

                                }

                            }
                            
                        }

                        // $promotion = Promotion::find($customer->promotion_id);
            
                        // if($promotion) {

                        //     if($bank_account_transaction->amount >= $promotion->min) {

                        //         $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

                        //     } else {

                        //         $bonus = 0;

                        //     }

                        // } else {

                        //     $bonus = 0;

                        // }
                        
                        $total_amount = $bank_account_transaction->amount;

                        $this->depositApi($brand, $bank_account_transaction, $customer, $total_amount);

                    } else {

                        $bank_account_transaction->update([
                            'status' => 4,
                        ]);

                    }

                } else {

                    $bank_account_transaction->update([
                        'status' => 6,
                    ]);

                }

            }

        }

        return response()->json(['status' => true]);

    }

    public function depositPromotion($brand) {

        $seconds = (int)date('s');

        if($seconds > 9) {

            $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->whereStatus(7)->orderBy('transfer_at','desc')->take(10)->get();

            foreach($bank_account_transactions as $bank_account_transaction) {

                if($bank_account_transaction->bank_id == 1) {
        
                    //SCB
                    $customer = Customer::whereBrandId($brand->id)->whereBankAccountScb($bank_account_transaction->bank_account)->where('status_manual','=',0)->whereCodeBank($bank_account_transaction->code_bank)->first();

                } else if ($bank_account_transaction->bank_id == 4) {

                    //Kbank
                    $customer = Customer::whereBrandId($brand->id)->whereBankAccountKbank($bank_account_transaction->bank_account)->where('status_manual','=',0)->first();

                } else if ($bank_account_transaction->bank_id == 5) {

                    //Krungsri
                    $customer = Customer::whereBrandId($brand->id)->whereBankAccountKrungsri($bank_account_transaction->bank_account)->where('status_manual','=',0)->first();

                } else if ($bank_account_transaction->bank_id == 0) {

                    $telephone = $bank_account_transaction->bank_account;

                    $t1 = substr($telephone,0,3);
                
                    $t2 = substr($telephone,3);
                
                    $telephone = $t1.'-'.$t2;
                    //truemoney
                    $customer = Customer::whereBrandId($brand->id)->whereTelephone($telephone)->where('status_manual','=',0)->first();

                }

                if($customer) {

                    // $promotion = Promotion::find($customer->promotion_id);

                    // if($promotion) {

                    //     if($bank_account_transaction->amount >= $promotion->min) {

                    //         $promotion_id = $promotion->id;

                    //         $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

                    //     } else {

                    //         $promotion_id = 0;

                    //         $bonus = 0;

                    //     }

                    // } else {

                    //     $promotion_id = 0;

                    //     $bonus = 0;

                    // }

                    $total_amount = $bank_account_transaction->amount;
                        
                    $promotion_cost = PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

                    if($promotion_cost) {

                        $total_turn_over = ($promotion_cost->amount + $promotion_cost->bonus) * $promotion_cost->promotion->turn_over;

                        if($promotion_cost && ($customer->credit < $total_turn_over)) {

                            if($customer->credit < $promotion_cost->promotion->min_break_promotion) {

                                $promotion_cost->update([
                                    'status' => 1,
                                ]);

                            } else {

                                $bank_account_transaction->update([
                                    'status' => 7,
                                ]);

                            }

                        } else {

                            $this->depositApi($brand, $bank_account_transaction, $customer, $total_amount);

                        }
                        

                    } else {

                        $this->depositApi($brand, $bank_account_transaction, $customer, $total_amount);

                    }
                    

                } else {

                    $bank_account_transaction->update([
                        'status' => 4,
                    ]);

                }

            }

        }

    }

    public function depositApi($brand, $bank_account_transaction, $customer, $total_amount) {

        DB::beginTransaction();

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['amount'] = $total_amount;

        $data['customer_id'] = $customer->id;

        if($brand->game_id == 1) {

            $data['agent_order'] = $customer->agent_order;

        }

        $api_deposit = $api->deposit($data);

        $result_deposit = true;

        if($brand->game_id == 1) {

            if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                //customer online
                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                $result_deposit = false;

            } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                //api error
                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                $result_deposit = false;

            }

            $refer_id = 0;

        } else {

            if($api_deposit['status'] === false) {

                $bank_account_transaction->update([
                    'status' => 0,
                ]);

                $result_deposit = false;

            }

            $refer_id = 0;

            if(isset($api_deposit['data']['ref'])) {

                $refer_id = $api_deposit['data']['ref'];

            }

        }

        if($result_deposit == true) {

            $bank_account_transaction->update([
                'status' => 2,
            ]);

            // if($promotion ) {
                
            //     PromotionCost::whereCustomerId($customer->id)->update([
            //         'status' => 1
            //     ]);

            //     if($bank_account_transaction->amount >= $promotion->min) {

            //         PromotionCost::create([
            //             'brand_id' => $brand->id,
            //             'promotion_id' => $promotion->id,
            //             'customer_id' => $customer->id,
            //             'username' => $customer->username,
            //             'amount' => $bank_account_transaction->amount,
            //             'bonus' => $bonus,
            //             'status' => 0,
            //         ]);

            //         $customer_deposit = CustomerDeposit::create([
            //             'brand_id' => $brand->id,
            //             'customer_id' => $customer->id,
            //             'game_id' => $brand->game_id,
            //             'promotion_id' => ($promotion) ? $promotion->id : 0,
            //             'bank_account_id' => $bank_account_transaction->bank_account_id,
            //             'name' => $customer->name,
            //             'username' => $customer->username,
            //             'amount' => $bank_account_transaction->amount,
            //             'bonus' => $bonus,
            //             'type_deposit' => 2,
            //             'status' => 1,
            //         ]);

            //     } else {

            //         $customer_deposit = CustomerDeposit::create([
            //             'brand_id' => $brand->id,
            //             'customer_id' => $customer->id,
            //             'game_id' => $brand->game_id,
            //             'promotion_id' => 0,
            //             'bank_account_id' => $bank_account_transaction->bank_account_id,
            //             'name' => $customer->name,
            //             'username' => $customer->username,
            //             'amount' => $bank_account_transaction->amount,
            //             'bonus' => $bonus,
            //             'type_deposit' => 2,
            //             'status' => 1,
            //         ]);

            //     }

            // } else {

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

            // }

            $bank_account_transaction->update([
                'customer_deposit_id' => $customer_deposit->id, 
            ]);

            $customer->update([
                'promotion_id' => 0,
                'refer_id' => $refer_id,
            ]);

            BankAccountHistory::create([
                'brand_id' => $brand->id,
                'bank_account_id' => $bank_account_transaction->bank_account_id,
                'table_id' => $customer_deposit->id,
                'user_id' => 0,
                'table' => 'customer_deposits',
                'amount' => $bank_account_transaction->amount,
                'type' => 1,
            ]);

            CustomerCreditHistory::create([
                'brand_id' => $brand->id,
                'customer_id' => $customer->id,
                'customer_deposit_id' => $customer_deposit->id,
                'amount_before' => $customer->credit,
                'amount' => $total_amount,
                'amount_after' => $customer->credit + $total_amount,
                'type' => 1,
            ]);

            $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            // if($promotion) {

            //     if($bank_account_transaction->amount >= $promotion->min) {

            //         $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." พร้อมกับ​โบนัส ".$bonus." เครดิต (".$promotion->name.") \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

            //     } else {

            //         $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

            //     }

            // } else {

                $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

            // }

            $line_api = new LineApi();

            $line_api->token = $brand->line_token;

            $line_api->channel_secret = $brand->line_channel_secret;

            $push = $line_api->pushMessage($customer->line_user_id, $message);

            if($brand->line_notify_token) {

                $message_line_notify = "เติมเงิน\n";
                $message_line_notify .= "------------------- \n";
                $message_line_notify .= "ลูกค้า : ". $customer->name."\n";

                // if($promotion) {

                //     if($bank_account_transaction->amount >= $promotion->min) {

                //         $message_line_notify .= "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." พร้อมกับ​โบนัส ".$bonus." เครดิต (".$promotion->name.") \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

                //     } else {

                //         $message_line_notify .= "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

                //     }

                // } else {

                    $message_line_notify .= "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

                // }

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

        }

        DB::commit();

    }

}
