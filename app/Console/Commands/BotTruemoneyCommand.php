<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\BotEvent;
use App\Models\BankAccount;
use App\Helpers\BotTrueMoney;
use App\Models\BankAccountOtp;
use Illuminate\Console\Command;
use App\Models\BankAccountRefer;
use App\Models\BankAccountTransaction;

class BotTruemoneyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:truemoney';

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
        $bank_accounts = BankAccount::whereStatusBot(1)->whereBankId(0)->get();
        
        foreach($bank_accounts as $bank_account) { 

            if($bank_account->status_bot == 1) {

                BotEvent::create([
                    'brand_id' => $bank_account->brand_id,
                    'event' => 'truemoney start transaction '.$bank_account->account,
                ]);

                $this->botTrueMoney($bank_account);

            }

        }
    }



    public function setTruemoneyOtp($bank_account) {

        $data = [
            'username' => $bank_account->username,
            'password' => $bank_account->password,
            'pin' => $bank_account->pin
        ];

        $bot_true_money = new BotTrueMoney($data);

        $login_otp = $bot_true_money->RequestLoginOTP();

        BotEvent::create([
            'brand_id' => $bank_account->brand_id,
            'event' => 'truemoney debug sms otp '.json_encode($login_otp),
        ]);

        if($login_otp['code'] == 'MAS-200') {

            BotEvent::create([
                'brand_id' => $bank_account->brand_id,
                'event' => 'truemoney send sms otp '.json_encode($login_otp),
            ]);

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

                    BotEvent::create([
                        'brand_id' => $bank_account->brand_id,
                        'event' => 'truemoney submit sms otp '.json_encode($submit_otp),
                    ]);

                    $bank_account_otp->update([
                        'status' => 1,
                    ]);

                    

                }

                $ii = $loop;
                
            }
        }

    }

    public function botTrueMoney($bank_account) {

        $seconds = date('s');

        $data = [
            'username' => $bank_account->username,
            'password' => $bank_account->password,
            'pin' => $bank_account->pin
        ];

        $bot_true_money = new BotTrueMoney($data);

        // $bot_true_money->Login();

        $result = $bot_true_money->getTransaction();

        $status = $result['code'];

        BotEvent::create([
            'brand_id' => $bank_account->brand_id,
            'event' => 'transaction truemoney check status '.json_encode($result),
        ]);

        if($status == 'HTC-200') {

            $transactions = $result['data']['activities'];

            BotEvent::create([
                'brand_id' => $bank_account->brand_id,
                'event' => json_encode($transactions),
            ]);

            foreach($transactions as $transaction) {

                if ($transaction['title'] === 'รับเงินจาก') {
                    # code...
                    $dateTime = $transaction['date_time'];
                    
                    $dt = Carbon::parse(Carbon::createFromFormat('d/m/y H:i',$dateTime));
                    
                    $account = str_replace('-','',$transaction['transaction_reference_id']);
                    
                    $unix_time = $dt->timestamp;

                    $amount = str_replace('+','',$transaction['amount']);

                    $amount = str_replace(',','',$transaction['amount']);
        
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
                            'status_transaction' => ($bank_account->brand->status_bot_deposit == 0) ? 2 : 0
                        ]);

                    }

                } 
        
            }

        } else {

            BotEvent::create([
                'brand_id' => $bank_account->brand_id,
                'event' => 'truemoney sms expire status = '.$bank_account->username.' '.json_encode($result),
            ]);

            $this->setTruemoneyOtp($bank_account);

        }

    }
}
