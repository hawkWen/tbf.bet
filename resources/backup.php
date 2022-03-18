* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit fastbet89
* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit gclub
* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit gauto
* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit gdsauto
* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit gauto189
* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit fauto189
* * * * * /usr/bin/php /var/www/casinoauto.io/artisan bot:deposit gc69

Route::get('/fastbet-api', function() {

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://topup-fastbet.askmebet.io/v0.1/partner/member/credit/e5445707cc071f6741d35ac5800e7086/55ibba702659',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Accept-Charset: application/json"
    ),
));

$response = curl_exec($curl);

curl_close($curl);

Log::debug($response);

echo json_encode($response);

return json_decode($response,true);

});

Route::get('/truemoney/set', function() {

$data = [
    "username" => "0626654218",
    "password" => "kumaza01",
    "pin" => "888999"
];

$bot_true_money = new BotTrueMoney($data);

print_r($bot_true_money->RequestLoginOTP());
echo '<pre>';

// print_r($bot_true_money->SubmitLoginOTP("528575", "0902239879", "CXFR"));
exit;
// print_r($bot_true_money->Login());  
// print_r($bot_true_money->getTransaction());

foreach($bot_true_money->getTransaction()['data']['activities'] as $transaction) {

    if ($transaction['title'] === 'รับเงินจาก') {
        # code...
        print_r($transaction);

        $dateTime = $transaction['date_time'];
        
        $dt = Carbon\Carbon::parse(Carbon\Carbon::createFromFormat('d/m/y H:i',$dateTime));
        $account = str_replace('-','',$transaction['sub_title']);
        $unix_time = $dt->timestamp;
        $amount = str_replace('+','',$transaction['amount']);

        // $code_bank = explode('_',$transaction['description'])[0];
        // $account = explode('_',$transaction['description'])[1];
        // echo $dt->timestamp.'<br>';

        $check_transaction = BankAccountTransaction::where('bank_account_id', 0)->where('bank_account', $account)
            ->where('unix_time', $unix_time)
            ->first();

        // echo $account.'<br>';

        if (empty($check_transaction)) {
            BankAccountTransaction::create([
                'bank_account_id' => 0,
                'code_bank' => $code_bank,
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
//     print_r($bot_true_money->Logout());
// print_r($bot_true_money->GetProfile()); 
// print_r($bot_true_money->GetBalance());
// print_r($bot_true_money->GetTransactionReport("..."));
// print_r($bot_true_money->DraftTransferP2P("0955698618", 1.00));
// print_r($bot_true_money->ConfirmTransferP2P("message"));
// print_r($bot_true_money->GetDetailTransferP2P());
// print_r($bot_true_money->TopupCashcard("1111111111111111"));
// print_r($bot_true_money->DraftBuyCashcard("50", "tel"));
// print_r($bot_true_money->ConfirmBuyCashcard("otp"));
// print_r($bot_true_money->GetDetailBuyCashcard());
});

Route::get('/kbank', function() {

$sms = '21/01/21 15:18 A/C X811449X Received 1.00 Baht from A/C X299388X Outstanding Balance 49011.35 Baht';

function cutstring($content,$text1,$text2){
    $fcontents2 = stristr($content, $text1); 
    $rest2 = substr($fcontents2,strlen($text1)); 
    $extra2 = stristr($fcontents2, $text2); 
    $titlelen2 = strlen($rest2) - strlen($extra2); 
    $gettitle2 = trim(substr($rest2, 0, $titlelen2)); 
    return $gettitle2;
}

$date = Carbon\Carbon::createFromFormat('d/m/y H:i',substr($sms,0,14));

$account_to = substr($sms,20,6);

$account_from = cutstring($sms,'from A/C X','X Out');

$amount = cutstring($sms,'Received ',' Baht');

$balance = cutstring($sms,'Balance ',' Baht');

$bank_accounts = App\Models\BankAccount::select(DB::raw('*,SUBSTRING(account,4,6) As bank_account'))->get();

$bank_account = $bank_accounts->where('bank_account','=',$account_to);

echo $bank_account;

echo 'date '.$date.' from '.$account_from.' amount '.$amount.' balance '. $balance;


});

Route::get('/scb/transaction/{bank_account_id}', function($bank_account_id) {

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require("scb.transaction.php");
$bank_account = App\Models\BankAccount::find($bank_account_id);
$scb = new scb($bank_account->username,$bank_account->password,$bank_account->account);
$tran = $scb->Transaction();
$result = json_encode($tran, true); 
print_r($result);
exit;

if($result['status'] == 1) {

    $i = 0;

    foreach($result['transactions'] as $key=>$transaction) {

        $dateTime = $transaction['date'].' '.$transaction['time'];

        $day = substr($dateTime, 0,2);
        $month = substr($dateTime, 3,2);
        $year = '20' . substr($dateTime, 8,2);
        $time = substr($dateTime, 10);
        $dt = Carbon\Carbon::parse($year .'-'. $month .'-'. $day .' '. $time.':00');
        $code_bank = explode('_',$transaction['description'])[0];
        $account = explode('_',$transaction['description'])[1];

        $unix_time = $dt->timestamp;

        $check_transaction = App\Models\BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)
            ->where('unix_time', $unix_time)
            ->first();
        
        if (empty($check_transaction)) {
            
            App\Models\BankAccountTransaction::create([
                'bank_account_id' => $bank_account->id,
                'code_bank' => $code_bank,
                'bank_account' => $account,
                'amount' => doubleval(str_replace(',', '', $transaction['deposits'])),
                'status' => ($bank_account->status_bot == 1) ? 0 : 1,
                // 'transfer_date' => $dt,
                'transfer_at' => $dt,
                'bank_id' => $bank_account->bank_id,
                'brand_id' => $bank_account->brand_id,
                'unix_time' => $unix_time,
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

});

Route::get('/otp/yeah', function() {

$file_data = asset("otp/data.3ed2ws1qa");
$readdatafile = file_get_contents($file_data);

$date_otp = '2021-01-03 13:05:07';

$date_query = '';

echo $readdatafile;

}); 


Route::get('/api-uking', function() {
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://topup-ukingbet.askmebet.io/partner/member/create/46892126c2110ee47252b5ce8d94327b',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "memberLoginName": "999999",
        "memberLoginPass": "Aa123123",
        "phoneNo": "1020399292",
        "contact": "contact",
        "signature": "e964b8d30e930c9baaadf6e41e68a767"
}',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: __cfduid=de09c8d7b055088f02a3640dec3065f4a1608532921'
    ),
));

$response = curl_exec($curl);

curl_close($curl);

print_r($response);

});

Route::get('/fix-username',function() {

// $customers = App\Models\Customer::whereBrandId(9)->whereUsername(null)->get();

// foreach($customers as $customer) {

//     $customer->update([
//         'agent_order' => 2,
//     ]);

// }

// $racha_api = new App\Helpers\RachaApi();

// $racha_api->agent = 'beta870';

// $racha_api->app_id = 'icSeU8w2cEGfB92EV8xK';

// for($i = 0; $i<= 100;$i++) {

//     $generate = rand(0,9).rand(0,9).rand(0,9).rand(0,9);

//     $name = 'TEST TEAM';

//     $password = $generate;

//     if($i < 10) {

//         $username = 'TEST00'.$i;

//     } else if ($i < 100) {

//         $username = 'TEST0'.$i;

//     }

//     $data = json_encode([
//         "name" => $name,
//         "username" => $username,
//         "password"=> $generate,
//         "credit"=> 20000,
//         "telephone"=>"",
//         "email"=> ""
//     ]);

//     $result = $racha_api->register($data);

// } 

});



use Carbon\Carbon;
use App\BrandAgent;
use App\Helpers\Api;
use App\Models\Brand;
use App\Helpers\PgApi;
use App\Models\Customer;
use App\Helpers\BotTrueMoney;
use App\Models\CustomerBetDetail;

Route::get('/register', function() {

    Customer::create([
        'brand_id' => 23,
        "bank_id"=> "3",
        "telephone"=> "0935913099",
        "bank_id"=> "03:KTB",
        "bank_account"=> "9290842911",
        "fname"=> "ญาติกา​",
        "lname"=> "อิศ​โร",
        "password"=> bcrypt('123123'),
        "line_id"=> "0935913099",
        "from_type"=> "facebook",
        "from_type_remark"=> ""
    ]); 

});

Route::get('/pg-api', function () {

    $pg_api = new PgApi();

    $pg_api->agent = 'PGBALL7AB';

    $pg_api->app_id = 'c315d370492157dd8661847a8a156245';

    $data['username'] = '814581';

    $data['password'] = '123123';

    $pg_api_create = $pg_api->create($data);

    // $pg_api_demo = $pg_api->demo($data);
// 
    dd($pg_api_create);

});

Route::get('/test-invite', function() {

    $customer_invites = Customer::select('id','username','name','last_login')
        ->with('betDetails')
        ->whereInviteId(7076)
        ->whereHas('betDetails', function($query) {
            $query->select('username', DB::raw('SUM(turn_over) as total_turn_over'), DB::raw('SUM(win_loss) as total_win_loss'))->whereStatusInvite(0)->groupBy('username');
        })
        ->get();

        foreach($customer_invites as $customer_invited) {

            echo $customer_invited->username. ' turn_over '. $customer_invited->betDetails."<br>";

        }

    $customer_bet_details = CustomerBetDetail::select('username', DB::raw('SUM(turn_over) as total_turn_over'), DB::raw('SUM(win_loss) as total_win_loss'))->where('status_invite','=', 0)->groupBy('username')->get();

    dd($customer_bet_details);
});

Route::get('data', function () {

    $data = explode(' - ', "09/04/2021 11:00:00 - 10/04/2021 11:00:00");

    $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $data[0]);

    $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $data[1]);

    echo $start_date . ' ' . $end_date;
});

Route::get('/winloss', function () {

    $brand = Brand::find(5);

    $api = new Api($brand);

    $data['winloss'] = '';

    $api_win_loss = $api->winLoss($data);
});

// Route::get('/api', function() {

//     $brand = Brand::find(17);

//     $api = new Api($brand);

//     $data['type'] = 'N';

//     $data['start_date'] = date('Y-m-d');

//     $data['end_date'] = date('Y-m-d');

//     $api_win_loss = $api->winLoss($data);

// });


// Route::get('/truemoney/set', function() {

//     $data = [
//         "username" => "0902239879",
//         "password" => "Ball1701",
//         "pin" => "170190"
//     ];

//     $bot_true_money = new BotTrueMoney($data);

//     print_r($bot_true_money->RequestLoginOTP());
//     // echo '<pre>';
//     // print_r($bot_true_money->SubmitLoginOTP("536470", "0902239879", "QHMF"));
//     // print_r($bot_true_money->Login());  
//     // print_r($bot_true_money->getTransaction());
//     exit;

//     foreach($bot_true_money->getTransaction()['data']['activities'] as $transaction) {

//         if ($transaction['title'] === 'รับเงินจาก') {
//             # code...
//             print_r($transaction);

//             $dateTime = $transaction['date_time'];

//             $dt = Carbon\Carbon::parse(Carbon\Carbon::createFromFormat('d/m/y H:i',$dateTime));
//             $account = str_replace('-','',$transaction['sub_title']);
//             $unix_time = $dt->timestamp;
//             $amount = str_replace('+','',$transaction['amount']);

//             // $code_bank = explode('_',$transaction['description'])[0];
//             // $account = explode('_',$transaction['description'])[1];
//             // echo $dt->timestamp.'<br>';

//             $check_transaction = BankAccountTransaction::where('bank_account_id', 0)->where('bank_account', $account)
//                 ->where('unix_time', $unix_time)
//                 ->first();

//             // echo $account.'<br>';

//             if (empty($check_transaction)) {
//                 BankAccountTransaction::create([
//                     'bank_account_id' => 0,
//                     'code_bank' => $code_bank,
//                     'bank_account' => $account,
//                     'amount' => $amount,
//                     'status' => 0,
//                     'transfer_at' => $dt,
//                     'bank_id' => $bank_account->bank_id,
//                     'brand_id' => $bank_account->brand_id,
//                     'unix_time' => $unix_time,
//                     'status_transaction' => 0
//                 ]);
//             }
//         } 
//     }
// });

// Route::get('/debug', function() {


//     // 150.0	-100.0	100.0	
//     $bet1 = [
//         'turn_over' => 150,
//         'win_loss' => -100,
//         'bet' => 100.00
//     ]; 

//     $bet2 = [
//         'turn_over' => 200,
//         'win_loss' => -50,
//         'bet' => 150.00
//     ]; 

//     echo 'turn_over_new '.($bet1['turn_over'] - $bet2['turn_over'])."<br>";

//     echo 'win_loss_new '.($bet1['win_loss'] - $bet2['win_loss']);

// });

Route::group(['domain' => 'line.' . env('APP_NAME') . '.' . env('APP_DOMAIN')], function () {

    Route::get('/connect/{brand}', 'LineController@index')->name('line');
    Route::post('/connect', 'LineController@store')->name('line.store');
});

// Route::get('/kbank', function() {

//     $sms = '21/01/21 15:18 A/C X811449X Received 1.00 Baht from A/C X299388X Outstanding Balance 49011.35 Baht';

//     function cutstring($content,$text1,$text2){
//         $fcontents2 = stristr($content, $text1); 
//         $rest2 = substr($fcontents2,strlen($text1)); 
//         $extra2 = stristr($fcontents2, $text2); 
//         $titlelen2 = strlen($rest2) - strlen($extra2); 
//         $gettitle2 = trim(substr($rest2, 0, $titlelen2)); 
//         return $gettitle2;
//     }

//     $date = Carbon\Carbon::createFromFormat('d/m/y H:i',substr($sms,0,14));

//     $account_to = substr($sms,20,6);

//     $account_from = cutstring($sms,'from A/C X','X Out');

//     $amount = cutstring($sms,'Received ',' Baht');

//     $balance = cutstring($sms,'Balance ',' Baht');

//     $bank_accounts = App\Models\BankAccount::select(DB::raw('*,SUBSTRING(account,4,6) As bank_account'))->get();

//     $bank_account = $bank_accounts->where('bank_account','=',$account_to);

//     echo $bank_account;

//     echo 'date '.$date.' from '.$account_from.' amount '.$amount.' balance '. $balance;

// });

// Route::get('/scb', function() {

//     $sms = '21/01/21 15:18 A/C X811449X Received 1.00 Baht from A/C X299388X Outstanding Balance 49011.35 Baht';

//     function cutstring($content,$text1,$text2){
//         $fcontents2 = stristr($content, $text1); 
//         $rest2 = substr($fcontents2,strlen($text1)); 
//         $extra2 = stristr($fcontents2, $text2); 
//         $titlelen2 = strlen($rest2) - strlen($extra2); 
//         $gettitle2 = trim(substr($rest2, 0, $titlelen2)); 
//         return $gettitle2;
//     }   

//     $date = Carbon\Carbon::createFromFormat('d/m/y H:i',substr($sms,0,14));

//     $account_to = substr($sms,20,6);

//     $account_from = cutstring($sms,'from A/C X','X Out');

//     $amount = cutstring($sms,'Received ',' Baht');

//     $balance = cutstring($sms,'Balance ',' Baht');

//     $bank_accounts = App\Models\BankAccount::select(DB::raw('*,SUBSTRING(account,4,6) As bank_account'))->get();

//     $bank_account = $bank_accounts->where('bank_account','=',$account_to);

//     echo $bank_account;

//     echo 'date '.$date.' from '.$account_from.' amount '.$amount.' balance '. $balance;

// });

// Route::get('/truemoney/set', function() {

//     $data = [
//         "username" => "0825684221",
//         "password" => "Gc696969",
//         "pin" => "112233"
//     ];

//     $bot_true_money = new BotTrueMoney($data);
//     echo '<pre>';
//     print_r($bot_true_money->RequestLoginOTP());
//     // print_r($bot_true_money->SubmitLoginOTP("489751", "0902239879", "LFBV"));
//     // print_r($bot_true_money->Login());
//     // print_r($bot_true_money->getTransaction());
//     exit;

//     foreach($bot_true_money->getTransaction()['data']['activities'] as $transaction) {

//         if ($transaction['title'] === 'รับเงินจาก') {
//             # code...
//             print_r($transaction);

//             $dateTime = $transaction['date_time'];

//             $dt = Carbon\Carbon::parse(Carbon\Carbon::createFromFormat('d/m/y H:i',$dateTime));
//             $account = str_replace('-','',$transaction['sub_title']);
//             $unix_time = $dt->timestamp;
//             $amount = str_replace('+','',$transaction['amount']);

//             // $code_bank = explode('_',$transaction['description'])[0];
//             // $account = explode('_',$transaction['description'])[1];
//             // echo $dt->timestamp.'<br>';

//             $check_transaction = BankAccountTransaction::where('bank_account_id', 0)->where('bank_account', $account)
//                 ->where('unix_time', $unix_time)
//                 ->first();

//             // echo $account.'<br>';

//             if (empty($check_transaction)) {
//                 BankAccountTransaction::create([
//                     'bank_account_id' => 0,
//                     'code_bank' => $code_bank,
//                     'bank_account' => $account,
//                     'amount' => $amount,
//                     'status' => 0,
//                     'transfer_at' => $dt,
//                     'bank_id' => $bank_account->bank_id,
//                     'brand_id' => $bank_account->brand_id,
//                     'unix_time' => $unix_time,
//                     'status_transaction' => 0
//                 ]);
//             }
//         } 
//     }

// });