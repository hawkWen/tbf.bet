<?php

namespace App\Http\Controllers\Bot;

use App\User;
use Carbon\Carbon;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Helpers\BotApi;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\BotEvent;
use App\Models\Customer;
use App\Helpers\GClubApi;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Helpers\BotSCBPin;
use App\Helpers\FastbetApi;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Helpers\FastbetBotApi;
use App\Models\BankAccountOtp;
use App\Models\CustomerDeposit;
use App\Models\BankAccountRefer;
use App\Models\CustomerWithdraw;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\BankAccountTransaction;

class BotController extends Controller
{
    //
    public function index() {

        $brands = Brand::all();

        return view('bot.index');

    }

    public function restart($brand_id) {

        $brand = Brand::find($brand_id);

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

    public function start(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        // if($brand->game_id == 5) {

        //     $fastbet_bot_api = new FastbetBotApi();

        //     $fastbet_bot_api->ip = $brand->server_api;

        //     $fastbet_bot_api->username = $brand->username;

        //     $fastbet_bot_api->password = $brand->password;

        //     $fastbet_bot_api->token = 'yeahteam';

        //     $start = $fastbet_bot_api->startStop('start');

        // }

    }

    public function stop(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        // aaz`

    }

    public function bank($bank_sub_domain) {

        $brand = Brand::whereSubdomain($bank_sub_domain)->first();

        return view('bot.bank', compact('brand'));

    }

    public function bankStore(Request $request) {

        $input = $request->all();

        $bank_account = BankAccount::whereBrandId($input['brand_id'])->whereBankId(1)->whereIn('type',[0,1])->first();

        DB::beginTransaction();

        try {

            // dd($seconds);

            DB::beginTransaction();

            $api = new BotSCB($bank_account);

            $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');
    
            $token = Helper::decryptString($bank_account->token, 1, 'base64');

            $api->setLogin($app_id, $token);
            $api->login();
            $api->setAccountNumber($bank_account->account);

            $transactions = $api->getTransaction();

            if (!empty($transactions)) {
                foreach($transactions as $transaction) {
                    // print_r($transaction);
                    $check_transaction = BankAccountTransaction::where('brand_id','=',$bank_account->brand_id)
                        ->where('bank_account_id', $bank_account->id)->where('bank_account', preg_replace('/[^0-9]+/', '', $transaction['bank_account']))
                        ->where('unix_time', Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp)
                        ->first();
                    if (empty($check_transaction)) {
                        echo 'check';
                        BankAccountTransaction::create([
                            'bank_account_id' => $bank_account->id,
                            'brand_id' => $bank_account->brand_id,
                            'code_bank' => $transaction['code'],
                            'bank_account' => preg_replace('/[^0-9]+/', '', $transaction['bank_account']),
                            'amount' => doubleval(str_replace(",","",$transaction['deposits'])),
                            'status' => ($bank_account->status_bot == 0) ? 1 : 0,
                            'transfer_at' => Carbon::parse($transaction['date']. ' ' .$transaction['time']),
                            'bank_id' => $bank_account->bank_id,
                            'unix_time' => Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp,
                            'status_transaction' => 0
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollback();

            abort(500, $e->getMessage());

        }

        DB::commit();

    }

    public function setBrand(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $user = User::whereUsername($input['code'])->first();

        if(!$user) {
            return response()->json([
                'code' => 500,
                'message' => 'ไม่พบบัญชีนี้'
            ]);
        }

        $brand = Brand::find($user->brand_id);

        DB::commit();

        return response()->json([
            'code' => 0,
            'message' => 'Success',
            'brand' => $brand,
        ]);

    }

    public function brandLists($brand_id) {

        $brand = Brand::find($brand_id);

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)->get();

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->take(10)->orderBy('created_at','desc')->get();

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->take(10)->orderBy('created_at','desc')->get();

        return view('agent.bot.lists', \compact('brand','bank_account_transactions','customer_deposits','customer_withdraws'));

    }

    // public function bank(Request $request) {

    //     $input = $request->all();

    //     $brand = Brand::find($input['brand_id']);

    //     $bank_accounts = BankAccount::whereBrandId($brand->id)->whereIn('type',[0,1])->whereStatusBot(1)->get();

    //     $brand->update([
    //         'bot_ip' => Helper::getIPLocation()
    //     ]);

    //     foreach($bank_accounts as $bank_account) {

    //         if($bank_account->bank_id == 4) {

    //             //KBANK
    //             $this->botKbank($bank_account);

    //         }

    //         if($bank_account->bank_id == 5) {

    //             //KRUNGSRI
    //             $this->botKrungSri($bank_account);

    //         }

    //         if($bank_account->bank_id == 1) {
    //             //SCB
    //             $this->botSCB($bank_account);

    //         }

    //     }

    //     return response()->json(['status' => true, 'brand' => $brand]);

    // }

    public function botKrungSri($bank_account) {

        try {
            $trans = json_decode(\file_get_contents($bank_account->url_data.'?bankAccount='.$bank_account->account), true);
            $bank_account['transactions'] = $trans[1]['Transaction'];

            if($bank_account['transactions'] != null) {
                $i = 1;
                foreach($bank_account['transactions'] as $key => $transaction) {

                    $day = substr($transaction['dateTime'], 0,2);
                    $month = substr($transaction['dateTime'], 3,2);
                    $year = '20' . substr($transaction['dateTime'], 6,2);
                    $time = substr($transaction['dateTime'], 10);
                    $dt = Carbon::parse($year .'-'. $month .'-'. $day .' '. $time.':00');
                    $account = substr(explode(' ',$transaction['lists'])[1],-7);

                    if ($key !== 0) {
                        $day_2 = substr($bank_account['transactions'][$key-1]['dateTime'], 0,2);
                        $month_2 = substr($bank_account['transactions'][$key-1]['dateTime'], 3,2);
                        $year_2 = '20' . substr($bank_account['transactions'][$key-1]['dateTime'], 6,2);
                        $time_2 = substr($bank_account['transactions'][$key-1]['dateTime'], 10);
                        $dt_2 = Carbon::parse($year_2 .'-'. $month_2 .'-'. $day_2 .' '. $time_2.':00');
                        if ($dt->eq($dt_2)) {
                            print_r('key 2');
                            $dt = Carbon::parse($year .'-'. $month .'-'. $day .' '. $time.':'.$i);
                        }
                    };
                    $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)->where('unix_time', $dt->timestamp)->first();

                    if (empty($check_transaction)) {
                        BankAccountTransaction::create([
                            'bank_account_id' => $bank_account->id,
                            'code' => null,
                            'bank_account' => $account,
                            'amount' => doubleval(str_replace(',', '', $transaction['credit'])),
                            'status' => ($bank_account->status_bot == 1) ? 0 : 1,
                            'transfer_date' => $dt,
                            'transfer_at' => $dt,
                            'bank_id' => $bank_account->bank_id,
                            'brand_id' => $bank_account->brand_id,
                            'unix_time' => $dt->timestamp,
                            'status_transaction' => 0
                        ]);
                    }

                    if ($i >= 60) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }


            // BotEvent::create([
            //     'brand_id' => $bank_account->brand->id,
            //     'event' => 'บอทธนาคารเริ่มทำงาน​ '. $bank_account->brand->name. ' KRUNGSRI '.$bank_account->account. ' '.$bank_account->name,
            // ]);

            DB::commit();

        } catch (Exception $e) {

            BotEvent::create([
                'brand_id' => $bank_account->brand->id,
                'event' => 'บอทธนาคารปิด​ '. $bank_account->brand->name. ' KRUNGSRI '.$bank_account->account. ' '.$bank_account->name,
            ]);

            DB::rollback();
            abort(500, $e->getMessage());
        }

    }

    public function botKbank($bank_account) {

        try {
            $username = $bank_account->username; //K-Cyber username
            $password = $bank_account->password; //K-Cyber password
            $account_number = $bank_account->account; //เลขบัญชี
            $kbank = new BotKbank($bank_account->username, $bank_account->password, $bank_account->username.'.txt');
            if($kbank->CheckSession() == false) {

                BankAccountLog::create([
                    'log' => 'session status false',
                ]);

            } else {

                BankAccountLog::create([
                    'log' => json_encode($kbank),
                ]);

            }

            if (!$kbank->CheckSession()) {
                $kbank->Login();
            }

            $item['transactions'] = $kbank->GetTodayStatement($account_number);
            if (!empty($item['transactions'])) {
                foreach($item['transactions'] as $transaction) {

                    $bank_account = $transaction['A/C Number'] ? str_replace("-","",str_replace("x","",$transaction['A/C Number'])) : null;
                    $day = substr($transaction['Date/Time'], 0,2);
                    $month = substr($transaction['Date/Time'], 3,2);
                    $year = '20' . substr($transaction['Date/Time'], 6,2);
                    $time = substr($transaction['Date/Time'], 9);
                    $dt = Carbon::parse($year .'-'. $month .'-'. $day .' '. $time);

                    $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('unix_time', $dt->timestamp)->first();

                    if (!empty($check_transaction) && $bank_account !== null) {
                        $check_transaction_2 = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $bank_account)->where('unix_time', $dt->timestamp)->first();
                        if(empty($check_transaction_2)) {
                            BankAccountTransaction::create([
                                'bank_account_id' => $bank_account->id,
                                'code' => null,
                                'bank_account' => $bank_account,
                                'amount' => doubleval(str_replace(",","",$transaction['Deposit (THB)'])),
                                'status' => 0,
                                'transfer_  date' => $dt,
                                'transfer_at' => $dt,
                                'bank_id' => $bank_account->bank_id,
                                'unix_time' => $dt->timestamp,
                                'description' => $transaction['Details'],
                                'note' => $transaction['Transaction Type'],
                                'status' => ($bank_account->status_bot == 1) ? 0 : 1
                            ]);
                        }
                    }

                    if (empty($check_transaction)) {
                        BankAccountTransaction::create([
                            'bank_account_id' => $bank_account->id,
                            'code' => null,
                            'bank_account' => $bank_account,
                            'amount' => doubleval(str_replace(",","",$transaction['Deposit (THB)'])),
                            'status' => ($bank_account->status_bot == 1) ? 0 : 1,
                            'description' => $transaction['Details'],
                            'note' => $transaction['Transaction Type'],
                            'transfer_date' => $dt,
                            'transfer_at' => $dt,
                            'bank_id' => $bank_account->bank_id,
                            'unix_time' => $dt->timestamp,
                            'status_transaction' => 0
                        ]);
                    }
                }
            }


            // BotEvent::create([
            //     'brand_id' => $bank_account->brand->id,
            //     'event' => 'บอทธนาคารเริ่มทำงาน​ '. $bank_account->brand->name. ' KBANK '.$bank_account->account. ' '.$bank_account->name,
            // ]);

            DB::commit();

        } catch (Exception $e) {

            BotEvent::create([
                'brand_id' => $bank_account->brand->id,
                'event' => 'บอทธนาคารปิด​ '. $bank_account->brand->name. ' KBANK '.$bank_account->account. ' '.$bank_account->name,
            ]);

            DB::rollback();
            abort(500, $e->getMessage());
        }

    }

    public function botSCB($bank_account) {

        try {

            DB::beginTransaction();

            $api = new BotSCB($bank_account);

            $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');
    
            $token = Helper::decryptString($bank_account->token, 1, 'base64');

            $api->setLogin($app_id, $token);
            $api->login();
            $api->setAccountNumber($bank_account->account);

            $transactions = $api->getTransaction();

            if (!empty($transactions)) {
                foreach($transactions as $transaction) {
                    $check_transaction = BankAccountTransaction::where('brand_id','=',$bank_account->brand_id)
                        ->where('bank_account_id', $bank_account->id)->where('bank_account', preg_replace('/[^0-9]+/', '', $transaction['bank_account']))
                        ->where('unix_time', Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp)
                        ->first();
                    if (empty($check_transaction)) {
                        BankAccountTransaction::create([
                            'bank_account_id' => $bank_account->id,
                            'brand_id' => $bank_account->brand_id,
                            'code_bank' => $transaction['code'],
                            'bank_account' => preg_replace('/[^0-9]+/', '', $transaction['bank_account']),
                            'amount' => doubleval(str_replace(",","",$transaction['deposits'])),
                            'status' => ($bank_account->status_bot == 0) ? 1 : 0,
                            'transfer_at' => Carbon::parse($transaction['date']. ' ' .$transaction['time']),
                            'bank_id' => $bank_account->bank_id,
                            'unix_time' => Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp,
                            'status_transaction' => 0
                        ]);
                    }
                }
            }

            DB::commit();

        } catch (Exception $e) {

            BotEvent::create([
                'brand_id' => $bank_account->brand->id,
                'event' => 'บอทธนาคารหยุดทำงาน '. $bank_account->brand->name. ' SCB '.$bank_account->account. ' '.$bank_account->name,
            ]);

            DB::rollback();

            abort(500, $e->getMessage());

        }

    }

    public function deposit(Request $request) {

        // $input = $request->all();

        // $seconds = (int)date('s');

        // if($seconds > 9) {

        //     $brand = Brand::find($input['brand_id']);

        //     $bank_account_transaction = BankAccountTransaction::whereBrandId($input['brand_id'])->whereStatus(0)->orderBy('transfer_at','desc')->first();

        //     if($bank_account_transaction) {

        //         $bank_account_unique = BankAccountTransaction::where('transfer_at','=',$bank_account_transaction->transfer_at)
        //             ->whereBankAccount($bank_account_transaction->bank_account)
        //             ->where('status','!=',0)
        //             ->get();

        //         if($bank_account_transaction && $bank_account_unique->count() == 0) {

        //             if($bank_account_transaction->bank_id == 1) {
        
        //                 //SCB
        //                 $customer = Customer::whereBrandId($brand->id)->whereBankAccountScb($bank_account_transaction->bank_account)->where('status_manual','=',0)->whereCodeBank($bank_account_transaction->code_bank)->first();

        //             } else if ($bank_account_transaction->bank_id == 4) {

        //                 //Kbank
        //                 $customer = Customer::whereBrandId($brand->id)->whereBankAccountKbank($bank_account_transaction->bank_account)->where('status_manual','=',0)->first();

        //             } else if ($bank_account_transaction->bank_id == 5) {

        //                 //Krungsri
        //                 $customer = Customer::whereBrandId($brand->id)->whereBankAccountKrungsri($bank_account_transaction->bank_account)->where('status_manual','=',0)->first();

        //             } else if ($bank_account_transaction->bank_id == 0) {

        //                 $telephone = $bank_account_transaction->bank_account;

        //                 $t1 = substr($telephone,0,3);
                    
        //                 $t2 = substr($telephone,3);
                    
        //                 $telephone = $t1.'-'.$t2;
        //                 //truemoney
        //                 $customer = Customer::whereBrandId($brand->id)->whereTelephone($telephone)->where('status_manual','=',0)->first();

        //             }

        //             $promotion = Promotion::find($customer->promotion_id);
        
        //             if($promotion) {

        //                 if($bank_account_transaction->amount >= $promotion->min) {

        //                     $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

        //                 } else {

        //                     $bonus = 0;

        //                 }

        //             } else {

        //                 $bonus = 0;

        //             }

        //             $total_amount = $bank_account_transaction->amount + $bonus;

        //             if($brand->game_id == 1) {

        //                 $response = Bot::depositGclub($brand,$customer,$total_amount);

        //                 if($response['online'] === true && $response['status'] === false) {
        //                     //customer online
        //                     $bank_account_transaction->update([
        //                         'status' => 5,
        //                     ]);

        //                     return abort(500, 'API ERROR TRY AGAIN');

        //                 } else if ($response['online'] === false && $response['status'] === false) {
        //                     //api error
        //                     $bank_account_transaction->update([
        //                         'status' => 0,
        //                     ]);

        //                     return abort(500, 'API ERROR TRY AGAIN');

        //                 }
                        
        //             } else if ($brand->game_id == 5) {

        //                 $fastbet_api = new FastbetApi();

        //                 $fastbet_api->agent = $brand->agent_username;

        //                 $fastbet_api->app_id = $brand->app_id;

        //                 $data['username'] = $customer->username;

        //                 $data['amount'] = $total_amount;
                        
        //                 $fastbet_api_deposit = $fastbet_api->deposit($data);

        //                 if($fastbet_api_deposit['code'] != 0) {

        //                     //api error
        //                     $bank_account_transaction->update([
        //                         'status' => 0,
        //                     ]);

        //                     return abort(500, 'API ERROR TRY AGAIN');

        //                 }

        //             } 

        //             $bank_account_transaction->update([
        //                 'status' => 2,
        //             ]);

        //             $customer->update([
        //                 'promotion_id' => 0,
        //             ]);

        //             if($promotion) {

        //                 PromotionCost::create([
        //                     'brand_id' => $brand->id,
        //                     'promotion_id' => $promotion->id,
        //                     'customer_id' => $customer->id,
        //                     'username' => $customer->username,
        //                     'amount' => $bank_account_transaction->amount,
        //                     'bonus' => $bonus,
        //                     'status' => 0,
        //                 ]);

        //             }

        //             $customer_deposit = CustomerDeposit::create([
        //                 'brand_id' => $brand->id,
        //                 'customer_id' => $customer->id,
        //                 'game_id' => $brand->game_id,
        //                 'promotion_id' => ($promotion) ? $promotion->id : 0,
        //                 'bank_account_id' => $bank_account_transaction->bank_account_id,
        //                 'name' => $customer->name,
        //                 'username' => $customer->username,
        //                 'amount' => $bank_account_transaction->amount,
        //                 'bonus' => $bonus,
        //                 'type_deposit' => 2,
        //                 'status' => 1,
        //             ]);

        //             BankAccountHistory::create([
        //                 'brand_id' => $brand->id,
        //                 'bank_account_id' => $bank_account_transaction->bank_account_id,
        //                 'table_id' => $customer_deposit->id,
        //                 'user_id' => 0,
        //                 'table' => 'customer_deposits',
        //                 'amount' => $bank_account_transaction->amount,
        //                 'type' => 1,
        //             ]);

        //             $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

        //             if($promotion) {

        //                 $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." พร้อมกับ​โบนัส ".$bonus." เครดิต (".$promotion->name.") \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

        //             } else {

        //                 $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

        //             }

        //             $line_api = new LineApi();

        //             $line_api->token = $brand->line_token;

        //             $line_api->channel_secret = $brand->line_channel_secret;

        //             $push = $line_api->pushMessage($customer->line_user_id, $message);

        //         } else {

        //             $bank_account_transaction->update([
        //                 'status' => 6,
        //             ]);

        //         }

        //     }

        // }

        // return response()->json(['status' => true]);

    }

    public function withdraw(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $customer_withdraw = CustomerWithdraw::whereBrandId($brand->id)->whereTypeWithdraw(1)->whereStatus(0)->first();

        if($customer_withdraw) {

            $customer = Customer::find($customer_withdraw->customer_id);

            $bank_account = BankAccount::whereBrandId($brand->id)->whereIn('type',[0,3,10,11])->where('bank_id','=',1)->where('status_bot','=',1)->where('active','=',0)->first(); 
            
            if(!$bank_account) {

                abort(500);
                exit();
    
            }
    
            if($bank_account) {

                if($bank_account->type == 10 || $bank_account->type == 11) {

                    $this->botWithdrawPin($brand,$bank_account,$customer,$customer_withdraw);

                } else {

                    $this->botWithdraw($brand,$bank_account,$customer,$customer_withdraw);

                }

                BotEvent::create([
                    'brand_id' => $brand->id,
                    'event' => 'บอทโอนให้ลูกค้า '. $customer->name . ' USERNAME '. $customer->username. ' เป็นจำนวนเงิน '. $customer_withdraw->amount,
                ]);

                if($customer_withdraw->promotionCost) {

                    $customer_withdraw->promotionCost->update([
                        'status' => 1,
                    ]);

                    PromotionCost::find($customer_withdraw->promotion_cost_id)->update([
                        'status' => 2,
                    ]);

                }

            } else {

                $customer_withdraw->update([
                    'status' => 0,
                    'type_withdraw' => 2,
                ]);

            }

        }

        DB::commit();

        return response()->json(['status' => true]);

    }

    public function botWithdrawPin($brand,$bank_account,$customer,$customer_withdraw) {
            
        // $bank_account->update([
        //     'active' => 1,
        // ]);

        $api = new BotSCBPin($bank_account);
        $api->setBaseParam(); // token, account number ดึงจาก db ไป where account ที่จะให้บอททำงาน

        $transfer = $api->transfer($customer->bank_account,$customer->bank->code_scb,$customer_withdraw->amount); //เลขบัญชี รหัสธนาคาร จำนวนเงิน

        if($transfer['status'] == 1) {

            DB::beginTransaction();

            $customer_withdraw->update([
                'bank_account_id' => $bank_account->id,
                'status' => 2,
                'remark' => $transfer,
            ]);

            BankAccountHistory::create([
                'brand_id' => $bank_account->brand_id,
                'bank_account_id' => $bank_account->id,
                'user_id' => 0,
                'table_id' => $customer_withdraw->id,
                'table' => 'customer_withdraws',
                'amount' => $customer_withdraw->amount,
                'type' => 2,
            ]);

            $bank_account->decrement('amount', $customer_withdraw->amount);

            $line_api = new LineApi();

            $line_api->token = $brand->line_token;

            $line_api->channel_secret = $brand->line_channel_secret;

            $message = 'ระบบได้ถอนเงินให้กับ '.$customer->username.' จำนวน '.$customer_withdraw->amount.' เรียบร้อยแล้วค่ะ ขอบคุณค่ะ';

            $push = $line_api->pushMessage($customer->line_user_id, $message);
            
            $bank_account->update([
                'active' => 0,
            ]);

            DB::commit();

        } else {

            $customer_withdraw->update([
                'status' => 4,
                'remark' => $transfer['msg'],
                'type_withdraw' => 2,
            ]);

        }

    }

    public function botWithdraw($brand,$bank_account,$customer,$customer_withdraw) {
            
        // $bank_account->update([
        //     'active' => 1,
        // ]);

        $api = new BotSCB($bank_account);

        $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');

        $token = Helper::decryptString($bank_account->token, 1, 'base64');

        $api->setLogin($app_id, $token);

        $api->login();
        
        $bank_account->update([
            'active' => 0,
        ]);

        $api->setAccountNumber($bank_account->account);

        $transfer = $api->transfer($customer->bank_account,$customer->bank->code_scb,$customer_withdraw->amount); //เลขบัญชี รหัสธนาคาร จำนวนเงิน

        if($transfer['status'] == 1) {

            DB::beginTransaction();

            $customer_withdraw->update([
                'bank_account_id' => $bank_account->id,
                'status' => 2,
                'remark' => $transfer,
            ]);

            BankAccountHistory::create([
                'brand_id' => $bank_account->brand_id,
                'bank_account_id' => $bank_account->id,
                'user_id' => 0,
                'table_id' => $customer_withdraw->id,
                'table' => 'customer_withdraws',
                'amount' => $customer_withdraw->amount,
                'type' => 2,
            ]);

            $bank_account->decrement('amount', $customer_withdraw->amount);

            $line_api = new LineApi();

            $line_api->token = $brand->line_token;

            $line_api->channel_secret = $brand->line_channel_secret;

            $message = 'ระบบได้ถอนเงินให้กับ '.$customer->username.' จำนวน '.$customer_withdraw->amount.' เรียบร้อยแล้วค่ะ ขอบคุณค่ะ';

            $push = $line_api->pushMessage($customer->line_user_id, $message);
            
            $bank_account->update([
                'active' => 0,
            ]);

            DB::commit();

        } else {
            $bank_account->update([
                'active' => 0,
            ]);

            $customer_withdraw->update([
                'status' => 4,
                'remark' => $transfer['msg'],
                'type_withdraw' => 2,
            ]);

        }
        $bank_account->update([
            'active' => 0,
        ]);

    }

    public function withdrawOtp($brand_id) {

        $brand = Brand::find($brand_id);

        return view('bot.withdraw.index', compact('brand'));

    }

    public function withdrawOtpStore(Request $request) {

        //place this before any script you want to calculate time
        // $time_start = microtime(true); 

        // //sample script
        // for($i=0; $i<1000; $i++){
        // //do anything
        // }

        // $time_end = microtime(true);

        // //dividing with 60 will give the execution time in minutes otherwise seconds
        // $execution_time = ($time_end - $time_start)/60;

        // //execution time of the script
        // echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';

        $input = $request->all();

        // DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $customer_withdraw = CustomerWithdraw::whereBrandId($brand->id)->whereTypeWithdraw(1)->whereStatus(0)->first();

        // dd($customer_withdraw);

        $result_withdraw['status'] = false;

        if($customer_withdraw) {

            $customer = Customer::find($customer_withdraw->customer_id);

            $bank_account = BankAccount::whereBrandId($brand->id)->whereType(7)->where('bank_id','=',1)->where('status_bot','=',1)->first();

            if($bank_account) {

                $result_withdraw = $this->botWithdrawSCBEasy($brand,$bank_account,$customer,$customer_withdraw);

                if($result_withdraw['status'] === true) {

                    $customer_withdraw->update([
                        'bank_account_id' => $bank_account->id,
                        'status' => 2,
                        'remark' => $transfer,
                    ]);

                    BankAccountHistory::create([
                        'brand_id' => $bank_account->brand_id,
                        'bank_account_id' => $bank_account->id,
                        'user_id' => 0,
                        'table_id' => $customer_withdraw->id,
                        'table' => 'customer_withdraws',
                        'amount' => $customer_withdraw->amount,
                        'type' => 2,
                    ]);

                    $bank_account->decrement('amount', $customer_withdraw->amount);

                    $line_api = new LineApi();

                    $line_api->token = $brand->line_token;

                    $line_api->channel_secret = $brand->line_channel_secret;

                    $message = 'ระบบได้ถอนเงินให้กับ '.$customer->username.' จำนวน '.$customer_withdraw->amount.' เรียบร้อยแล้วค่ะ ขอบคุณค่ะ';

                    $push = $line_api->pushMessage($customer->line_user_id, $message);

                    BotEvent::create([
                        'brand_id' => $brand->id,
                        'event' => 'บอทโอนให้ลูกค้า '. $customer->name . ' USERNAME '. $customer->username. ' เป็นจำนวนเงิน '. $customer_withdraw->amount,
                    ]);

                } else {

                    $customer_withdraw->update([
                        'type_withdraw' => 1,
                        'status' => 0,
                    ]);

                }

            } else {

                $customer_withdraw->update([
                    'status' => 3,
                    'type_withdraw' => 2,
                ]);

            }

        }

        // DB::commit();

        return response()->json(['status' => true, 'brand' => $brand, 'result_withdraw' => $result_withdraw]);

    }

    public function botWithdrawSCBEasy($brand,$bank_account,$customer,$customer_withdraw) {

        $scb_id = $bank_account->username;

        $scb_pass = $bank_account->password;

        $mobile_otp = 0;

        $bank_account_id = $bank_account->id;

        $bank_account = $bank_account->account;

        $customer_bank_account = $customer->bank_account;

        $customer_bank_code = $customer->bank->code_scb;

        $customer_amount = $customer_withdraw->amount;
            
        include('scb.withdraw.php');

        $send = get_otp_withdraw($scb_id,$scb_pass,$customer_amount,$customer_bank_account,$customer_bank_code,$mobile_otp,$bank_account);

        // dd($send);

        $result = [
            'status' => false,
            'msg' => null
        ];
        
        if($send['status']==1){

            $bank_account_refer = BankAccountRefer::create([
                'bank_account_id' => $bank_account_id,
                'refer' => $send['refer'],
            ]);

            $ii=0;

            $checktime=date("YmdHis");

            $loop=5;

            while($ii<$loop){
            
                $ii++;
            
                sleep(10);

                $bank_account_otp = BankAccountOtp::whereRefer($send['refer'])->whereStatus(0)->first();

                if($bank_account_otp['otp'] != null) {
                    $confirm_data=confirm_otp($bank_account_otp['otp'],$send['__VIEWSTATE'],$send['__VIEWSTATEGENERATOR'],$customer_bank_code,$bank_account);
                    if($confirm_data['status']==1){
                        $bank_account_otp->update([
                            'status' => 1,
                        ]);
                        $result['status'] = true;
                        $result['msg'] = 'โอนสำเร็จ';
                    }else{
                        $bank_account_otp->update([
                            'status' => 1,
                        ]);
                        $result['status'] = false;
                        $result['msg'] = $send['detail'];
                    }
                    $ii=$loop;
                    $otpin=true;
                }
                
            } 

            if(!$otpin){
                $result['status'] = false;
                $result['msg'] = 'รอ otp นานเกินไป otp มาไม่ถึง';
            }

        }else{

            $result['status'] = false;
            $result['msg'] = $send['detail'];

            // $customer_withdraw->update([
            //     'type_withdraw' => 2,
            // ]);

        }

        return $result;

    }

    public function otpCheck(Request $request) {

        $input = $request->all();

        $brand = Brand::whereCodeSms($input['code_sms'])->first();

        if($brand) {

            $bank_accounts = BankAccount::whereBrandId($brand->id)->whereIn('type',[7,8])->get();

            $data = collect([]);

            foreach($bank_accounts as $bank_account) {

                $data->push([
                    'bank_account' => $bank_account->account,
                    'type' => ($bank_account->type == 8) ? 'TrueMoney' : '027777777',
                    'url'=> 'https://bot.casinoauto.io/otp/get/' . $bank_account->id,
                ]);

            }

            return [
                'code' => 200,
                'data' => $data,
                'brand_name' => $brand->name,
                // 'url' => 'https://bot.casinoauto.io/otp/get/' . $bank_account->id,
            ];

        } else {

            return [
                'code' => 404,
                'url' => null
            ];

        }   
    }

}
