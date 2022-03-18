<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\BotEvent;
use App\Models\Customer;
use App\Models\BankAccount;
use Illuminate\Console\Command;
use App\Models\CustomerWithdraw;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class BotWithdrawCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:withdraw {brand_sub_domain}';

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

        $a = 0;

        while ($a <= 60) {
            # code...
            
            $this->withdraw($brand_sub_domain);
            
            $a += 20;

            sleep(20);

        }

    }

    public function withdraw($brand_sub_domain) {

        DB::beginTransaction();

        $brand = Brand::whereSubdomain($brand_sub_domain)->first();

        $customer_withdraw = CustomerWithdraw::whereBrandId($brand->id)->whereTypeWithdraw(1)->whereStatus(0)->first();

        if($customer_withdraw) {

            $customer = Customer::find($customer_withdraw->customer_id);

            $bank_account = BankAccount::whereBrandId($brand->id)->whereIn('type',[0,3])->where('bank_id','=',1)->where('status_bot','=',1)->first();

            if($bank_account) {

                $this->botWithdraw($brand,$bank_account,$customer,$customer_withdraw);

                BotEvent::create([
                    'brand_id' => $brand->id,
                    'event' => 'บอทโอนให้ลูกค้า '. $customer->name . ' USERNAME '. $customer->username. ' เป็นจำนวนเงิน '. $customer_withdraw->amount,
                ]);

            } else {

                $customer_withdraw->update([
                    'status' => 3,
                    'type_withdraw' => 2,
                ]);

            }

        }

        DB::commit();

        return response()->json(['status' => true, 'brand' => $brand]);

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


        } else {

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
}
