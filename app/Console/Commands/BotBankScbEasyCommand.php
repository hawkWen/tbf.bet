<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\BotEvent;
use App\Helpers\BotSCBEasy;
use App\Models\BankAccount;
use Illuminate\Console\Command;
use App\Models\BankAccountTransaction;

class BotBankScbEasyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:bank-scb-easy';

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
        $bank_accounts = BankAccount::whereStatusBot(1)->whereType(6)->get();

        $count = 0;

        $a = 0;

        while ($a <= 60) {
            # code...

            $random_time = rand(15,25);

            if($count < 60) {

                foreach($bank_accounts as $bank_account) {
                    $this->botSCBEasy($bank_account);
                }

                $a += $random_time;

                $count += $random_time;

                sleep($random_time);

                echo $a.'<br>'.$count;

            } else {

                echo $a.'<br>'.$count;

                break;

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
}
