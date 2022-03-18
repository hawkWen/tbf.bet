<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Models\BotEvent;
use App\Helpers\BotKbank;
use App\Helpers\BotSCBEasy;
use App\Models\BankAccount;
use App\Helpers\BotTrueMoney;
use App\Models\BankAccountOtp;
use Illuminate\Console\Command;
use App\Models\BankAccountRefer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\BankAccountTransaction;

class BotBankCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:bank';

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
        $bank_accounts = BankAccount::whereStatusBot(1)->get();

        $count = 0;

        $a = 0;

        while ($a <= 60) {
            # code...

            $random_time = rand(15,25);

            if($count < 60) {

                foreach($bank_accounts as $bank_account) {

                    // if($bank_account->bank_id == 4) {

                    //     //KBANK
                    //     $this->botKbank($bank_account);

                    // }

                    // if($bank_account->bank_id == 5) {

                    //     //KRUNGSRI
                    //     $this->botKrungSri($bank_account);

                    // }

                    if($bank_account->bank_id == 1) {
                        //SCB
                        if($bank_account->type == 0 || $bank_account->type == 1) {

                            $this->botSCB($bank_account);

                        } else if($bank_account->type == 6) {

                            // $this->botSCBEasy($bank_account);

                        }

                    }

                }

                $a += $random_time;

                $count += $random_time;

                sleep($random_time);

                // echo $a.'<br>'.$count;

            } else {

                // echo $a.'<br>'.$count;

                break;

            }

        }
    }

    // public function botKrungSri($bank_account) {

    //     $seconds = date('s');

    //     if($seconds != '01') {

    //         try {
    //             $trans = json_decode(\file_get_contents($bank_account->url_data.'?bankAccount='.$bank_account->account), true);
    //             $bank_account['transactions'] = $trans[1]['Transaction'];

    //             if($bank_account['transactions'] != null) {
    //                 $i = 1;
    //                 foreach($bank_account['transactions'] as $key => $transaction) {

    //                     $day = substr($transaction['dateTime'], 0,2);
    //                     $month = substr($transaction['dateTime'], 3,2);
    //                     $year = '20' . substr($transaction['dateTime'], 6,2);
    //                     $time = substr($transaction['dateTime'], 10);
    //                     $dt = Carbon::parse($year .'-'. $month .'-'. $day .' '. $time.':00');
    //                     $account = substr(explode(' ',$transaction['lists'])[1],-7);

    //                     if ($key !== 0) {
    //                         $day_2 = substr($bank_account['transactions'][$key-1]['dateTime'], 0,2);
    //                         $month_2 = substr($bank_account['transactions'][$key-1]['dateTime'], 3,2);
    //                         $year_2 = '20' . substr($bank_account['transactions'][$key-1]['dateTime'], 6,2);
    //                         $time_2 = substr($bank_account['transactions'][$key-1]['dateTime'], 10);
    //                         $dt_2 = Carbon::parse($year_2 .'-'. $month_2 .'-'. $day_2 .' '. $time_2.':00');
    //                         if ($dt->eq($dt_2)) {
    //                             $dt = Carbon::parse($year .'-'. $month .'-'. $day .' '. $time.':'.$i);
    //                         }
    //                     };

    //                     $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)->where('unix_time', $dt->timestamp)->first();

    //                     if (empty($check_transaction)) {
    //                         BankAccountTransaction::create([
    //                             'bank_account_id' => $bank_account->id,
    //                             'code' => null,
    //                             'bank_account' => $account,
    //                             'amount' => doubleval(str_replace(',', '', $transaction['credit'])),
    //                             'status' => ($bank_account->status_bot == 1) ? 0 : 1,
    //                             'transfer_date' => $dt,
    //                             'transfer_at' => $dt,
    //                             'bank_id' => $bank_account->bank_id,
    //                             'brand_id' => $bank_account->brand_id,
    //                             'unix_time' => $dt->timestamp,
    //                             'status_transaction' => 0
    //                         ]);
    //                     }

    //                     if ($i >= 60) {
    //                         $i = 0;
    //                     } else {
    //                         $i++;
    //                     }
    //                 }
    //             }


    //             BotEvent::create([
    //                 'brand_id' => $bank_account->brand->id,
    //                 'event' => 'บอทธนาคารเริ่มทำงาน​ '. $bank_account->brand->name. ' KRUNGSRI '.$bank_account->account. ' '.$bank_account->name,
    //             ]);

    //             DB::commit();

    //         } catch (Exception $e) {

    //             // BotEvent::create([
    //             //     'brand_id' => $bank_account->brand->id,
    //             //     'event' => 'บอทธนาคารปิด​ '. $bank_account->brand->name. ' KRUNGSRI '.$bank_account->account. ' '.$bank_account->name,
    //             // ]);

    //             DB::rollback();
    //             abort(500, $e->getMessage());
    //         }

    //     }

    // }

    // public function botKbank($bank_account) {

    //     $seconds = (int)date('s');

    //     if($seconds > 9) {

    //         try {
    //             $username = $bank_account->username; //K-Cyber username
    //             $password = $bank_account->password; //K-Cyber password
    //             $account_number = $bank_account->account; //เลขบัญชี
    //             $kbank = new BotKbank($bank_account->username, $bank_account->password, $bank_account->username.'.txt');
    //             if($kbank->CheckSession() == false) {

    //                 BankAccountLog::create([
    //                     'log' => 'session status false',
    //                 ]);

    //             } else {

    //                 BankAccountLog::create([
    //                     'log' => json_encode($kbank),
    //                 ]);

    //             }

    //             if (!$kbank->CheckSession()) {
    //                 $kbank->Login();
    //             }

    //             $item['transactions'] = $kbank->GetTodayStatement($account_number);
    //             if (!empty($item['transactions'])) {
    //                 foreach($item['transactions'] as $transaction) {

    //                     $bank_account = $transaction['A/C Number'] ? str_replace("-","",str_replace("x","",$transaction['A/C Number'])) : null;
    //                     $day = substr($transaction['Date/Time'], 0,2);
    //                     $month = substr($transaction['Date/Time'], 3,2);
    //                     $year = '20' . substr($transaction['Date/Time'], 6,2);
    //                     $time = substr($transaction['Date/Time'], 9);
    //                     $dt = Carbon::parse($year .'-'. $month .'-'. $day .' '. $time);

    //                     $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('unix_time', $dt->timestamp)->first();

    //                     if (!empty($check_transaction) && $bank_account !== null) {
    //                         $check_transaction_2 = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $bank_account)->where('unix_time', $dt->timestamp)->first();
    //                         if(empty($check_transaction_2)) {
    //                             BankAccountTransaction::create([
    //                                 'bank_account_id' => $bank_account->id,
    //                                 'code' => null,
    //                                 'bank_account' => $bank_account,
    //                                 'amount' => doubleval(str_replace(",","",$transaction['Deposit (THB)'])),
    //                                 'status' => 0,
    //                                 'transfer_  date' => $dt,
    //                                 'transfer_at' => $dt,
    //                                 'bank_id' => $bank_account->bank_id,
    //                                 'unix_time' => $dt->timestamp,
    //                                 'description' => $transaction['Details'],
    //                                 'note' => $transaction['Transaction Type'],
    //                                 'status' => ($bank_account->status_bot == 1) ? 0 : 1
    //                             ]);
    //                         }
    //                     }

    //                     if (empty($check_transaction)) {
    //                         BankAccountTransaction::create([
    //                             'bank_account_id' => $bank_account->id,
    //                             'code' => null,
    //                             'bank_account' => $bank_account,
    //                             'amount' => doubleval(str_replace(",","",$transaction['Deposit (THB)'])),
    //                             'status' => ($bank_account->status_bot == 1) ? 0 : 1,
    //                             'description' => $transaction['Details'],
    //                             'note' => $transaction['Transaction Type'],
    //                             'transfer_date' => $dt,
    //                             'transfer_at' => $dt,
    //                             'bank_id' => $bank_account->bank_id,
    //                             'unix_time' => $dt->timestamp,
    //                             'status_transaction' => 0
    //                         ]);
    //                     }
    //                 }
    //             }

    //             DB::commit();

    //         } catch (Exception $e) {

    //             // BotEvent::create([
    //             //     'brand_id' => $bank_account->brand->id,
    //             //     'event' => 'บอทธนาคารปิด​ '. $bank_account->brand->name. ' KBANK '.$bank_account->account. ' '.$bank_account->name,
    //             // ]);

    //             DB::rollback();
    //             abort(500, $e->getMessage());
    //         }

    //     }

    // }

    public function botSCB($bank_account) {

        if($bank_account->status_bot == 1) {

            $seconds = date('s');

            if($seconds > 9) {

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

                    // BotEvent::create([
                    //     'brand_id' => $bank_account->brand->id,
                    //     'event' => 'บอทธนาคารหยุดทำงาน '. $bank_account->brand->name. ' SCB '.$bank_account->account. ' '.$bank_account->name,
                    // ]);

                    DB::rollback();

                    abort(500, $e->getMessage());

                }

            }

        }

    }

    public function botSCBEasy($bank_account) {

        if($bank_account->status_bot == 1) {

            $seconds = (int)date('s');

            if($seconds > 9) {

                $scb = new BotSCBEasy($bank_account->username, $bank_account->password, $bank_account->account);

                BotEvent::create([
                    'brand_id' => 0,
                    'event' => json_encode($scb)
                ]);

                $result = $scb->Transaction();

                if($result['status'] == '1') {

                    BotEvent::create([
                        'brand_id' => 0,
                        'event' => 'transaction scb ok '.$bank_account->account. ' '.$result['status'].' '.json_encode($result['transactions']),
                    ]);

                    foreach($result['transactions'] as $key=>$transaction) {

                        // BotEvent::create([
                        //     'brand_id' => 0,
                        //     'event' => 'debug transaction '.$transaction['date'].' '.$transaction['time'].' '.$transaction['deposits'],
                        // ]);

                        $dateTime = $transaction['date'].' '.$transaction['time'].':00';
                        $dt = Carbon::createFromFormat('d/m/Y H:i:s', $dateTime);
                        $code_bank = explode('_',$transaction['description'])[0];
                        $account = explode('_',$transaction['description'])[1];

                        $unix_time = $dt->timestamp;

                        $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)
                            ->where('unix_time', $unix_time)
                            ->first();

                        if (empty($check_transaction)) {
                            BankAccountTransaction::create([
                                'bank_account_id' => $bank_account->id,
                                'code_bank' => $code_bank,
                                'bank_account' => $account,
                                'amount' => doubleval(str_replace(',', '', $transaction['deposits'])),
                                'status' => ($bank_account->status_bot == 1) ? 0 : 1,
                                'transfer_at' => $dt,
                                'bank_id' => $bank_account->bank_id,
                                'brand_id' => $bank_account->brand_id,
                                'unix_time' => $unix_time,
                                'status_transaction' => 0
                            ]);
                        }

                    }

                }

            }
            
        }

    }

    public function setTruemoneyOtp($bank_account) {

        $seconds = date('s');

        

            $data = [
                'username' => $bank_account->username,
                'password' => $bank_account->password,
                'pin' => $bank_account->pin
            ];

            $bot_true_money = new BotTrueMoney($data);

            $login_otp = $bot_true_money->RequestLoginOTP();

            // BotEvent::create([
            //     'brand_id' => $bank_account->brand_id,
            //     'event' => 'truemoney debug sms otp '.json_encode($login_otp),
            // ]);

            if($login_otp['code'] == 'MAS-200') {

                // BotEvent::create([
                //     'brand_id' => $bank_account->brand_id,
                //     'event' => 'truemoney send sms otp '.json_encode($login_otp),
                // ]);

                $bank_account_refer = BankAccountRefer::create([
                    'bank_account_id' => $bank_account->id,
                    'account' => $bank_account->account,
                    'refer' => $login_otp['data']['otp_reference']
                ]);

                $ii=0;

                $loop=5;

                while($ii<$loop){

                    $ii++;
                        
                    sleep(10);

                    $bank_account_otp = BankAccountOtp::whereRefer($bank_account_refer->refer)->whereStatus(0)->first();

                    if($bank_account_otp) {

                        $submit_otp = $bot_true_money->SubmitLoginOTP($bank_account_otp->otp, $bank_account->account, $bank_account_otp->refer);

                        // BotEvent::create([
                        //     'brand_id' => $bank_account->brand_id,
                        //     'event' => 'truemoney submit sms otp '.json_encode($submit_otp),
                        // ]);

                        $bank_account_otp->update([
                            'status' => 1,
                        ]);

                        

                    }

                    

                    BotEvent::create([
                        'brand_id' => $bank_account->brand_id,
                        'event' => 'truemoney wait sms otp',
                    ]);

                    $ii = $loop;
                    
                }

            }
    }

    public function botTrueMoney($bank_account) {

        $seconds = date('s');

        if($seconds != '01') {

            // BotEvent::create([
            //     'brand_id' => $bank_account->brand_id,
            //     'event' => 'truemoney start transaction',
            // ]);

            $data = [
                'username' => $bank_account->username,
                'password' => $bank_account->password,
                'pin' => $bank_account->pin
            ];

            $bot_true_money = new BotTrueMoney($data);

            $bot_true_money->Login();

            $result = $bot_true_money->getTransaction();

            $status = $result['code'];

            if($status == 'UPC-200') {

                $transactions = $result['data']['activities'];

                // BotEvent::create([
                //     'brand_id' => $bank_account->brand_id,
                //     'event' => 'transaction ok '.json_encode($transactions).'  '.json_encode($result),
                // ]);

                foreach($transactions as $transaction) {

                    if ($transaction['title'] === 'รับเงินจาก') {
                        # code...
                        $dateTime = $transaction['date_time'];
                        
                        $dt = Carbon::parse(Carbon::createFromFormat('d/m/y H:i',$dateTime));
                        
                        $account = str_replace('-','',$transaction['sub_title']);
                        
                        $unix_time = $dt->timestamp;

                        $amount = str_replace('+','',$transaction['amount']);
            
                        $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)
                            ->where('unix_time', $unix_time)
                            ->first();
            
                        if (empty($check_transaction)) {

                            BankAccountTransaction::create([
                                'bank_account_id' => $bank_account->id,
                                'code_bank' => 'true',
                                'bank_account' => $account,
                                'amount' => $amount,
                                'status' => 0,
                                'transfer_at' => $dt,
                                'bank_id' => $bank_account->bank_id,
                                'brand_id' => $bank_account->brand_id,
                                'unix_time' => $unix_time,
                                'status_transaction' => 0
                            ]);

                        }

                    } 
            
                }

            } else {

                // BotEvent::create([
                //     'brand_id' => $bank_account->brand_id,
                //     'event' => 'truemoney sms expire status = '.$bank_account->username.' '.json_encode($result),
                // ]);

                $this->setTruemoneyOtp($bank_account);

            }

        }

    }
}
