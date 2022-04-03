<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Brand;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Models\BotEvent;
use App\Helpers\BotKbank;
use App\Helpers\BotSCBPin;
use App\Helpers\BotSCBEasy;
use App\Models\BankAccount;
use App\Helpers\BotTrueMoney;
use App\Models\BankAccountOtp;
use Illuminate\Console\Command;
use App\Models\BankAccountRefer;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\BankAccountTransaction;

class BotBankSingleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:bank-single {brand_sub_domain}';

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

        $i = 0;

        set_time_limit(1);

        $random = rand(1,30);

        while ($i<=40)
        {
            if($i === $random) {

                $brand_sub_domain = $this->argument('brand_sub_domain');

                $brand = Brand::whereSubdomain($brand_sub_domain)->first();

                $bank_accounts = BankAccount::whereBrandId($brand->id)->whereBankId(1)->whereActive(0)->whereIn('type',[0,1,9,11])->get();
                
                foreach($bank_accounts as $bank_account) {   

                    if($bank_account->type == 9 || $bank_account->type == 11) {
                        $this->botSCBPin($bank_account);
                    } else {
                        $this->botSCB($bank_account);
                    }

                }

                $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereStatus(0)->get();

                if($customer_withdraws->count() == 0) {

                    $bank_account_outs = BankAccount::whereBrandId($brand->id)->whereBankId(1)->whereIn('type',[3,10])->get();

                    foreach($bank_account_outs as $bank_account_out) {

                        if($bank_account_out->type == 10) {
                            $this->botSCBPin($bank_account_out);
                        } else {
                            $this->botSCB($bank_account_out);
                        }

                    }

                }

            } 
            
            sleep(1);
            $i++;
        }


    }

    public function botSCB($bank_account) {

        if($bank_account->status_bot == 1) {

            try {

                DB::beginTransaction();
                
                $time_start = microtime(true);

                $api = new BotSCB($bank_account);

                $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');
        
                $token = Helper::decryptString($bank_account->token, 1, 'base64');

                $api->setLogin($app_id, $token);
                $api->login();
                $api->setAccountNumber($bank_account->account);

                $transactions = $api->getTransaction();

                $time_end = microtime(true);

                $execution_time = ($time_end - $time_start);

                $bank_account->update([
                    'last_execution_time' => $execution_time,
                ]);

                if (!empty($transactions)) {
                    foreach($transactions as $transaction) {
                        $check_transaction = BankAccountTransaction::where('brand_id','=',$bank_account->brand_id)
                            ->where('bank_account_id', $bank_account->id)->where('bank_account', preg_replace('/[^0-9]+/', '', $transaction['bank_account']))
                            ->where('unix_time', Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp)
                            ->first();
                        
                        if (empty($check_transaction)) {
                            $status = ($transaction['code_type'] === 'X2') ? 3 : 0;
                            BankAccountTransaction::create([
                                'bank_account_id' => $bank_account->id,
                                'brand_id' => $bank_account->brand_id,
                                'code' => $transaction['code_type'],
                                'code_bank' => $transaction['code'],
                                'bank_account' => preg_replace('/[^0-9]+/', '', $transaction['bank_account']),
                                'amount' => doubleval(str_replace(",","",$transaction['deposits'])),
                                'status' => $status,
                                'transfer_at' => Carbon::parse($transaction['date']. ' ' .$transaction['time']),
                                'bank_id' => $bank_account->bank_id,
                                'unix_time' => Carbon::parse($transaction['date']. ' ' .$transaction['time'])->timestamp,
                                'description' => $transaction['description'],
                            ]);
                        }
                    }
                }
            
                $bank_account->update([
                    'active' => 0,
                ]);
        

                DB::commit();

            } catch (Exception $e) {
            
                $bank_account->update([
                    'active' => 0,
                ]);
        

                DB::rollback();

                abort(500, $e->getMessage());

            }

        }
            
        $bank_account->update([
            'active' => 0,
        ]);

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
                            ]);
                        }

                    }

                }

            }
            
        }

    }

    public function botSCBPin($bank_account) {

        $time_start = microtime(true);

        $api= new BotSCBPin($bank_account);

        $api->setBaseParam(); // token, account number ดึงจาก db ไป where account ที่จะให้บอททำงาน

        $transactions = $api->getTransaction(); // จะใช้แค่เติมเงินเท่านั้น code x1, ถอน code x2
            
        $time_end = microtime(true);

        $execution_time = ($time_end - $time_start);

        $bank_account->update([
            'active' => 0,
            'last_execution_time' => $execution_time,
        ]);

        foreach($transactions as $transaction) {

            $dateTime = $transaction['date'].' '.$transaction['time'];
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime);
            if($transaction['code_type'] === 'X2') {
                $code_bank = $transaction['code'];
                $account = $transaction['description'];
            } else {
                $code_bank = explode('_',$transaction['description'])[0];
                $account = explode('_',$transaction['description'])[1];
            }
            $unix_time = $dt->timestamp;
            $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)
                ->where('unix_time', $unix_time)
                ->first();

            if (empty($check_transaction)) {
                $status = ($transaction['code_type'] === 'X2') ? 3 : 0;
                BankAccountTransaction::create([
                    'bank_account_id' => $bank_account->id,
                    'code' => $transaction['code_type'],
                    'code_bank' => $code_bank,
                    'bank_account' => $account,
                    'amount' => doubleval(str_replace(',', '', $transaction['deposits'])),
                    'status' => $status,
                    'transfer_at' => $dt,
                    'bank_id' => $bank_account->bank_id,
                    'brand_id' => $bank_account->brand_id,
                    'unix_time' => $unix_time,
                    'description' => $transaction['description'],
                ]);
            }

        }
    }
}
