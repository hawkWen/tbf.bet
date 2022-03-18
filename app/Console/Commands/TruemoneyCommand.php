<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Brand;
use App\Helpers\TrueMoney;
use App\Models\BankAccount;
use Illuminate\Console\Command;
use App\Models\BankAccountTransaction;

class TruemoneyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truemoney {brand_sub_domain}';

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

        $bank_accounts = BankAccount::whereBrandId($brand->id)->whereType(9)->whereBankId(0)->whereStatusBot(1)->get();

        foreach($bank_accounts as $bank_account) {

            $this->getTransaction($bank_account);

        }

        
    }

    public function getTransaction($bank_account) {

        $_TMN = array();
        $_TMN['tmn_key_id'] = $bank_account->tmn_one_id; //Key ID จากระบบ TMNOne
        $_TMN['mobile_number'] = $bank_account->account; //เบอร์ Wallet
        $_TMN['login_token'] = $bank_account->token; //login_token จากขั้นตอนการเพิ่มเบอร์ Wallet
        $_TMN['pin'] = $bank_account->pin; //PIN 6 หลักของ Wallet
        $_TMN['tmn_id'] = $bank_account->app_id; //tmn_id จากขั้นตอนการเพิ่มเบอร์ Wallet

        $TMNOne = new TrueMoney();
        $TMNOne->setData($_TMN['tmn_key_id'], $_TMN['mobile_number'], $_TMN['login_token'], $_TMN['tmn_id']);

        $TMNOne->loginWithPin6($_TMN['pin']); //Login เข้าระบบ Wallet ด้วย PIN
        $balance = $TMNOne->getBalance(); //ตรวจสอบยอดเงินคงเหลือ

        $transactions = $TMNOne->fetchTransactionHistory(date('Y-m-d',time()-86400),date('Y-m-d',time()+86400));

        if($balance == '') {
            $balance = 0;
        }
        
        $bank_account->update([
            'amount' => str_replace(',','',$balance)
        ]);

        foreach($transactions as $transaction) {

            if(isset($transaction['transaction_reference_id'])) {

                $dateTime = $transaction['date_time'];

                $dt = Carbon::parse(Carbon::createFromFormat('d/m/y H:i',$dateTime));

                $unix_time = $dt->timestamp;

                $amount = str_replace('+','',$transaction['amount']);
        
                $amount = str_replace(',','',$transaction['amount']);

                $account = str_replace('-','',$transaction['transaction_reference_id']);

                $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)
                    ->where('unix_time', $unix_time)
                    ->first();

                if (empty($check_transaction)) {

                    BankAccountTransaction::create([
                        'bank_account_id' => $bank_account->id,
                        'code' => 'X1',
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

    }
}
