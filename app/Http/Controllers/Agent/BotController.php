<?php

namespace App\Http\Controllers\Agent;

use App\User;
use Carbon\Carbon;
use App\Models\Brand;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\Customer;
use App\Helpers\GClubApi;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Helpers\FastbetApi;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Helpers\FastbetBotApi;
use App\Models\CustomerDeposit;
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

        // $brands = Brand::whereBrandId();

        return view('agent.bot.index');

    }

    public function restart(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        if($brand->game_id == 5) {

            $fastbet_bot_api = new FastbetBotApi();

            $fastbet_bot_api->ip = $brand->server_api;

            $fastbet_bot_api->username = $brand->agent_username;

            $fastbet_bot_api->pass = $brand->agent_password;

            $fastbet_bot_api->token = 'yeahteam';
            
            $stop = $fastbet_bot_api->startStop('stop');

            if($stop['code'] == 200) {

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

    public function bank(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id']);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->whereType(1)->whereStatusBot(1)->get();

        foreach($bank_accounts as $bank_account) {

            if($bank_account->bank_id == 4) {
                
                //KBANK
                $this->botKbank($bank_account);

            }

            if($bank_account->bank_id == 5) {

                //KRUNGSRI
                $this->botKrungSri($bank_account);

            }

            if($bank_account->bank_id == 1) {

                //SCB
                $this->botSCB($bank_account);

            }

        }

    }

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

            DB::commit();
        } catch (Exception $e) {
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

            DB::commit();
        } catch (Exception $e) {
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
                    
                    $check_transaction = BankAccountTransaction::where('brand_id','=',$bank_account->brand_id)->where('bank_account_id', $bank_account->id)->where('bank_account', preg_replace('/[^0-9]+/', '', $transaction['bank_account']))->where('unix_time', Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp)->first();
                    
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
            
            DB::rollback();
            
            abort(500, $e->getMessage());

        }

    }

    public function deposit(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $bank_account_transaction = BankAccountTransaction::whereBrandId($input['brand_id'])->orderBy('created_at','desc')->whereStatus(0)->first();

        if($bank_account_transaction) {
            
            if($bank_account_transaction->bank_id == 1) {

                //SCB
                $customer = Customer::whereBrandId($brand->id)->whereBankAccountScb($bank_account_transaction->bank_account)->whereCodeBank($bank_account_transaction->code_bank)->first();
    
            } else if ($bank_account_transaction->bank_id == 4) {
    
                //Kbank
                $customer = Customer::whereBrandId($brand->id)->whereBankAccountKbank($bank_account_transaction->bank_account)->first();
    
            } else if ($bank_account_transaction->bank_id == 5) {
    
                //Krungsri
                $customer = Customer::whereBrandId($brand->id)->whereBankAccountKrungsri($bank_account_transaction->bank_account)->first();
    
            }
    
            if($customer) {
    
                //Check customer deposit
                $customer_deposit = CustomerDeposit::whereCustomerId($customer->id)->whereStatus(1)->get();
    
                if($brand->game_id == 1) {
    
                    //Gclub
                    $response = $this->depositGclub($brand,$customer,$customer_deposit,$bank_account_transaction);
    
                } else if ($brand->game_id == 2) {
    
                    //Ufabet
                    $response = $this->depositUfa($brand,$customer,$customer_deposit,$bank_account_transaction);
    
                } else if ($brand->game_id == 3) {
    
                    //Racha Brand Customer BankAccount Transaction
                    $response = $this->depositRacha($brand,$customer,$customer_deposit,$bank_account_transaction);
    
                } else if ($brand->game_id == 5) {
                    
                    //Fastbet
                    $response = $this->depositFastbet($brand,$customer,$customer_deposit,$bank_account_transaction);

                }

                if($response['bonus'] > 0) {

                    $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." พร้อมกับ​โบนัส ".$response["bonus"]." เครดิต (".$response["promotion"].") \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

                } else {

                    $message = "ระบบได้เติมเงินให้ ".$customer->username." จำนวน ".$bank_account_transaction->amount." \n เรียบร้อยแล้วค่ะ ขอบคุณค่ะ";

                }

                $line_api = new LineApi();
        
                $line_api->token = $brand->line_token;
        
                $line_api->channel_secret = $brand->line_channel_secret;
        
                $push = $line_api->pushMessage($customer->line_user_id, $message);
    
            } else {
    
                $bank_account_transaction->update([
                    'status' => 4,
                ]);
    
            }
        }
        
        DB::commit();
        
    }

    public function depositRacha($brand,$customer,$customer_deposit,$bank_account_transaction) {

        $racha_api = new RachaApi();
    
        $racha_api->agent = $brand->agent_username;

        $racha_api->app_id = $brand->app_id;

        $promotion = Promotion::find($customer->promotion_id);

        if($promotion) {

            if($bank_account_transaction->amount >= $promotion->min) {

                $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

                PromotionCost::create([
                    'brand_id' => $brand->id,
                    'promotion_id' => $promotion->id,
                    'customer_id' => $customer->id,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'status' => 0,
                ]);

            } else {

                $bonus = 0;

            }

        } else {

            $bonus = 0;

        }

        $total_amount = $bank_account_transaction->amount + $bonus;

        if($customer->status_deposit == 0) {
            
            //New Member First Deposit
            $data = json_encode([
                "name" => $customer->name,
                "username" => 'RACHA'.$customer->bank_account_kbank,
                "password" => $customer->bank_account_kbank,
                "credit" => $bank_account_transaction->amount + $bonus,
                "telephone" => $customer->telephone,
                "avatar" => $customer->img_url,
                "email" => $customer->email
            ]);

            $response = $racha_api->register($data);

            if($response['code'] === 200) {

                $customer->update([
                    'username' => $response['response']['username'],
                    'password_generate' => $customer->bank_account_scb,
                    'password' => \bcrypt($customer->bank_account_scb),
                    'status_deposit' => 1,
                ]);

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
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

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

                if($brand->noty_register) {

                    $line_api = new LineApi();
                
                    $line_api->token = $brand->line_token;
            
                    $line_api->channel_secret = $brand->line_channel_secret;

                    $message1 = "ระบบได้สร้างไอดีเข้าเล่นให้ลูกค้าเรียบร้อย \n";
    
                    $message1 .= "Username: ".$customer->username." \n";
    
                    $message1 .= "Password: ".$customer->password_generate." \n";
                
                    $push = $line_api->pushMessage($customer->line_user_id, $message1);

                }

            } else {

                $bank_account_transaction->update([
                    'status' => 3,
                ]);

            }

        } else {
            
            $data = \json_encode([
                'username' => $customer->username,
                'amount' => $bank_account_transaction->amount + $bonus,
                'type' => 2,
            ]);

            $response = $racha_api->transfer($data);

            $bank_account_transaction->update([
                'status' => 2,
            ]);

            if($response['code'] == 200) {

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
                ]);

                BankAccountHistory::create([
                    'brand_id' => $brand->id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'user_id' => 0,
                    'table_id' => $customer_deposit->id,
                    'table' => 'customer_deposits',
                    'amount' => $bank_account_transaction->amount,
                    'type' => 1,
                ]);

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            } else {

                $bank_account_transaction->update([
                    'status' => 3,
                ]);

            }

        }

        if($promotion) {
            PromotionCost::create([
                'brand_id' => $brand->id,
                'promotion_id' => $promotion->id,
                'customer_id' => $customer->id,
                'username' => $customer->username,
                'amount' => $bank_account_transaction->amount,
                'bonus' => $bonus,
                'status' => 0,
            ]);
        }

        $customer->update([
            'promotion_id' => 0,
            'status_deposit' => 1,
        ]);

        return [
            'promotion' => ($promotion) ? $promotion->name : '',
            'bonus' => $bonus,
        ];
    }

    public function depositFastbet($brand,$customer,$customer_deposit,$bank_account_transaction) {

        //Call Api;
        $fastbet_api = new FastbetApi();
    
        $fastbet_api->agent = $brand->agent_username;

        $fastbet_api->api_key = $brand->app_id;

        $promotion = Promotion::find($customer->promotion_id);

        if($promotion) {

            if($bank_account_transaction->amount >= $promotion->min) {

                $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

            } else {

                $bonus = 0;

            }

        } else {

            $bonus = 0;

        }

        $total_amount = $bank_account_transaction->amount + $bonus;

        if($customer->status_deposit == 0) {

            $fastbet_bot_api = new FastbetBotApi();

            $fastbet_bot_api->ip = $brand->server_api;

            $fastbet_bot_api->username = $brand->agent_username;

            $fastbet_bot_api->name = substr($customer->bank_account ,-6);

            $fastbet_bot_api->pass = 'fb'.substr($customer->bank_account ,-6);

            $fastbet_bot_api->contact = $customer->name;

            $fastbet_bot_api->credit = $total_amount;

            $fastbet_bot_api_register = $fastbet_bot_api->register();

            $username = substr($customer->bank_account,-6);

            $password = 'fb'.substr($customer->bank_account ,-6);

            if($fastbet_bot_api_register['code'] = 200) {

                $customer->update([
                    'username' => $brand->agent_prefix.$username,
                    'password' => bcrypt($password),
                    'password_generate' => $password,
                    'status_deposit' => 1,
                ]);

            } else {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);
    
            }

            if($brand->noty_register) {

                $line_api = new LineApi();
            
                $line_api->token = $brand->line_token;
        
                $line_api->channel_secret = $brand->line_channel_secret;

                $message1 = "ระบบได้สร้างไอดีเข้าเล่นให้ลูกค้าเรียบร้อย \n";

                $message1 .= "Username: ".$brand->agent_prefix.$username." \n";

                $message1 .= "Password: ".$customer->password_generate." \n";
            
                $push = $line_api->pushMessage($customer->line_user_id, $message1);

            }

            $bank_account_transaction->update([
                'status' => 2,
            ]);

            $customer_deposit = CustomerDeposit::create([
                'brand_id' => $brand->id,
                'customer_id' => $customer->id,
                'game_id' => $brand->game_id,
                'promotion_id' => $customer->promotion_id,
                'bank_account_id' => $bank_account_transaction->bank_account_id,
                'name' => $customer->name,
                'username' => $customer->username,
                'amount' => $bank_account_transaction->amount,
                'bonus' => $bonus,
                'type_deposit' => 2,
                'status' => 1,
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

            $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

        } else {
            
            $fastbet_bot_api = new FastbetBotApi();

            $fastbet_bot_api->ip = $brand->server_api;

            $fastbet_bot_api->username = $brand->agent_username;

            $fastbet_bot_api->name = $customer->username;

            $fastbet_bot_api->credit = $total_amount;

            $fastbet_bot_api_deposit = $fastbet_bot_api->deposit();

            if($fastbet_bot_api_deposit['code'] == 200) {

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
                ]);

                BankAccountHistory::create([
                    'brand_id' => $brand->id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'user_id' => 0,
                    'table_id' => $customer_deposit->id,
                    'table' => 'customer_deposits',
                    'amount' => $bank_account_transaction->amount,
                    'type' => 1,
                ]);

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            } else {

                $bank_account_transaction->update([
                    'status' => 3,
                ]);

            }

        }

        if($promotion) {
            PromotionCost::create([
                'brand_id' => $brand->id,
                'promotion_id' => $promotion->id,
                'customer_id' => $customer->id,
                'username' => $customer->username,
                'amount' => $bank_account_transaction->amount,
                'bonus' => $bonus,
                'status' => 0,
            ]);
        }

        $customer->update([
            'promotion_id' => 0,
        ]);

        return [
            'promotion' => ($promotion) ? $promotion->name : '',
            'bonus' => $bonus,
        ];

    }

    public function depositUfa($brand,$customer,$bank_account_transaction) {

        $promotion = Promotion::find($customer->promotion_id);

        if($promotion) {

            if($bank_account_transaction->amount >= $promotion->min) {

                $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

            } else {

                $bonus = 0;

            }

        } else {

            $bonus = 0;

        }

        $total_amount = $bank_account_transaction->amount + $bonus;

        if($customer->username == '') {

            $response = json_decode(file_get_contents('http://45.77.40.179/ufabet-api/agent-test?add_member'),true);

            if($response['stats']) {

                $username = $response['username'];

                $change_password = \json_decode(file_get_contents('http://45.77.40.179/ufabet-api/password?reset_password&username='.$response['username'].'&old_password=aa123456&new_password=aa'.$customer->bank_account_kbank),true);

                if($change_password['stats'] == false) {

                    DB::rollback();
    
                    return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);

                }


                $customer->update([
                    'username' => $username,
                    'password_generate' => 'aa'.$customer->bank_account_kbank,
                    'password' => \bcrypt('aa'.$customer->bank_account_kbank)
                    
                ]);

                $deposit = json_decode(file_get_contents('http://45.77.40.179/ufabet-api/agent-test?deposit&username='.$customer->username.'&amount='.$total_amount),true);

                if($deposit['stats'] == false) {

                    DB::rollback();
    
                    return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);    

                }

            } else {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);

            }

            if($brand->noty_register) {

                $line_api = new LineApi();
            
                $line_api->token = $brand->line_token;
        
                $line_api->channel_secret = $brand->line_channel_secret;

                $message1 = "ระบบได้สร้างไอดีเข้าเล่นให้ลูกค้าเรียบร้อย \n";

                $message1 .= "Username: ".$customer->username." \n";

                $message1 .= "Password: ".$customer->password_generate." \n";
            
                $push = $line_api->pushMessage($customer->line_user_id, $message1);

            }

            // if($response['status_deposit']) {

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
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

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            // } else {

                // $bank_account_transaction->update([
                //     'status' => 3,
                // ]);

            // }

        } else {

            $deposit = json_decode(file_get_contents('http://45.77.40.179/ufabet-api/agent-test?deposit&username='.$customer->username.'&amount='.$total_amount),true);

            if($deposit['stats'] == false) {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);    

            }

            if($deposit) {

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
                ]);

                BankAccountHistory::create([
                    'brand_id' => $brand->id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'user_id' => 0,
                    'table_id' => $customer_deposit->id,
                    'table' => 'customer_deposits',
                    'amount' => $bank_account_transaction->amount,
                    'type' => 1,
                ]);

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            } else {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);

            }

        }

        if($promotion) {
            PromotionCost::create([
                'brand_id' => $brand->id,
                'promotion_id' => $promotion->id,
                'customer_id' => $customer->id,
                'username' => $customer->username,
                'amount' => $bank_account_transaction->amount,
                'bonus' => $bonus,
                'status' => 0,
            ]);
        }

        $customer->update([
            'promotion_id' => 0,
        ]);

        return [
            'promotion' => ($promotion) ? $promotion->name : '',
            'bonus' => $bonus,
        ];

        //add member    
        // http://45.77.40.179/ufabet-api/agent-test?add_member
        // return json_encode(['stats' => true, 'username' => $this->user_agent_name . $user, 'password' => $pass, 'phone' => $phone]);

        //reset_password
        // http://45.77.40.179/ufabet-api/password?reset_password&username=&old_password&new_password
        // echo json_encode(['stats'=>true]);

        //deposit
        // http://45.77.40.179/ufabet-api/agent-test?deposit&username=&amount=
        // return json_encode(['stats' => true, 'username' => $username, "money" => $money]);

        //withdraw
        // http://45.77.40.179/ufabet-api/agent-test?withdraw&username=&amount=
        // $result = json_encode(['stats' => true, 'msg' => 'success']);

        //balance
        // http://45.77.40.179/ufabet-api/agent-test?withdraw&username=&amount=
        // $json = json_encode(['Balance' => $cellData[9]], JSON_UNESCAPED_UNICODE);

        dd('ufabet',$brand,$customer,$bank_account_transaction);

    }

    public function depositGclub($brand,$customer,$customer_deposit,$bank_account_transaction) {

        $promotion = Promotion::find($customer->promotion_id);

        if($promotion) {

            if($bank_account_transaction->amount >= $promotion->min) {

                $bonus = Helper::bonusCalculator($bank_account_transaction->amount, $promotion);

            } else {

                $bonus = 0;

            }

        } else {

            $bonus = 0;

        }

        $total_amount = $bank_account_transaction->amount + $bonus;

        if($customer->username == '') {

            $response = json_decode(file_get_contents($brand->server_api.'/server-api/gclub?add_user&username='.$brand->agent_username.'&password='.$brand->agent_password.'&name='.$brand->line_id.'&pass=gc'.$customer->bank_account_scb),true);

            if($response['status']) {

                $username = $response['username'];

                $password = $response['password'];

                $total_amount = $bank_account_transaction->amount + $bonus;

                $customer->update([
                    'username' => $username,
                    'password_generate' => 'gc'.$customer->bank_account_scb,
                    'password' => \bcrypt('gc'.$customer->bank_account_scb)
                ]);

                $deposit = json_decode(file_get_contents($brand->server_api.'/server-api/gclub?deposit&username='.$brand->agent_username.'&password='.$brand->agent_password.'&user='.$response['username'].'&amount='.$total_amount),true);

                if($deposit['status'] == false) {

                    DB::rollback();
    
                    return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);    

                }

            } else {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);

            }

            if($brand->noty_register) {

                $line_api = new LineApi();
            
                $line_api->token = $brand->line_token;
        
                $line_api->channel_secret = $brand->line_channel_secret;

                $message1 = "ระบบได้สร้างไอดีเข้าเล่นให้ลูกค้าเรียบร้อย \n";

                $message1 .= "Username: ".$customer->username." \n";

                $message1 .= "Password: ".$customer->password_generate." \n";
            
                $push = $line_api->pushMessage($customer->line_user_id, $message1);

            }

            // if($response['status_deposit']) {

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
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

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            // } else {

                // $bank_account_transaction->update([
                //     'status' => 3,
                // ]);

            // }

        } else {

            // echo $brand->server_api.'/server-api/gclub?deposit&username='.$brand->agent_username.'&password='.$brand->agent_password.'&user='.$customer->username.'&amount='.$total_amount;

            $deposit = json_decode(file_get_contents($brand->server_api.'/server-api/gclub?deposit&username='.$brand->agent_username.'&password='.$brand->agent_password.'&user='.$customer->username.'&amount='.$total_amount),true);

            // dd($deposit);

            if($deposit['status'] == false) {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);    

            }

            if($deposit) {

                $bank_account_transaction->update([
                    'status' => 2,
                ]);

                $customer_deposit = CustomerDeposit::create([
                    'brand_id' => $brand->id,
                    'customer_id' => $customer->id,
                    'game_id' => $brand->game_id,
                    'promotion_id' => $customer->promotion_id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'name' => $customer->name,
                    'username' => $customer->username,
                    'amount' => $bank_account_transaction->amount,
                    'bonus' => $bonus,
                    'type_deposit' => 2,
                    'status' => 1,
                ]);

                BankAccountHistory::create([
                    'brand_id' => $brand->id,
                    'bank_account_id' => $bank_account_transaction->bank_account_id,
                    'user_id' => 0,
                    'table_id' => $customer_deposit->id,
                    'table' => 'customer_deposits',
                    'amount' => $bank_account_transaction->amount,
                    'type' => 1,
                ]);

                $bank_account_transaction->bankAccount->increment('amount', $bank_account_transaction->amount);

            } else {

                DB::rollback();

                return \redirect()->back()->withErrors(['API ERROR ลองใหม่อีกครั้งครับ']);

            }

        }

        if($promotion) {
            PromotionCost::create([
                'brand_id' => $brand->id,
                'promotion_id' => $promotion->id,
                'customer_id' => $customer->id,
                'username' => $customer->username,
                'amount' => $bank_account_transaction->amount,
                'bonus' => $bonus,
                'status' => 0,
            ]);
        }

        $customer->update([
            'promotion_id' => 0,
        ]);

        return [
            'promotion' => ($promotion) ? $promotion->name : '',
            'bonus' => $bonus,
        ];

    }

    public function withdraw(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $customer_withdraw = CustomerWithdraw::whereBrandId($brand->id)->whereTypeWithdraw(1)->whereStatus(0)->first();

        if($customer_withdraw) {

            $customer = Customer::find($customer_withdraw->customer_id);

            $bank_account = $brand->bankAccounts->where('type','=',3)->where('bank_id','=',1)->where('status_bot','=',1)->first();
 
            if($bank_account) {

                $this->botWithdraw($brand,$bank_account,$customer,$customer_withdraw);

            } else {

                $customer_withdraw->update([
                    'status' => 3,
                    'type_withdraw' => 2,
                ]);

            }

        } 

        DB::commit();

    }

    public function botWithdraw($brand,$bank_account,$customer,$customer_withdraw) {

        $api = new BotSCB($bank_account);

        $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');

        $token = Helper::decryptString($bank_account->token, 1, 'base64');

        $api->setLogin($app_id, $token);
        
        $api->login();

        $api->setAccountNumber($bank_account->account);

        $transfer = $api->Transfer($customer->bank_account,$customer->bank->code_scb,$customer_withdraw->amount); // เลขบัญชี รหัสธนาคาร จำนวนเงิน

        if($transfer['status'] == 1) {

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

            $line_api = new LineApi();
        
            $line_api->token = $brand->line_token;
    
            $line_api->channel_secret = $brand->line_channel_secret;


        } else {

            $customer_withdraw->update([
                'status' => 4,
                'remark' => $transfer['msg'],
                'type_withdraw' => 2,
            ]);

        }

    }
}
