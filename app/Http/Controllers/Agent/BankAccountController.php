<?php

namespace App\Http\Controllers\Agent;

use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Helpers\BotSCBPin;
use App\Models\BankAccount;
use App\Helpers\KrungsriApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class BankAccountController extends Controller
{
    //
    public function index() {

        $bank_accounts = BankAccount::whereBrandId(Auth::user()->brand_id)->get();

        $banks = Bank::where('id','!=',0)->get();

        $brand = Brand::find(Auth::user()->brand_id);

        return view('agent.bank-accounts.index', compact('bank_accounts','banks','brand'));

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        if($input['bank_id'] == 0) {

            $input['account'] = $input['username'];

        }

        BankAccount::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มบัญชีธนาคารสำเร็จ');

        return redirect()->back();

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account = BankAccount::find($input['bank_account_id']);

        $bank_account->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขบัญชีธนาคารเรียบร้อย');

        return \redirect()->back();

    }

    public function updateAmount(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $input['amount'] = \str_replace(',','',$input['amount']);

        $bank_account = BankAccount::find($input['bank_account_id']);

        if($bank_account->active == 1) {

            abort(500);
            exit();

        }

        $bank_account->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขบัญชีธนาคารเรียบร้อย');

        return \redirect()->back();

    }

    public function updateStatus(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account = BankAccount::find($input['bank_account_id']);

        $response = [];

        if($bank_account->bank_id == 5) {

            $krungsri_api = new KrungsriApi();

            $krungsri_api->username = $bank_account->username;

            $krungsri_api->password = $bank_account->password;

            $krungsri_api->account = $bank_account->account;

            $krungsri_api->action = ($input['status'] == 1) ? 'start' : 'stop';

            $response = $krungsri_api->run();

            if($response['code'] == 200) {

                $bank_account->update([
                    $input['type'] => $input['status']
                ]);

            }

        } else {

            $bank_account->update([
                $input['type'] => $input['status']
            ]);

        }

        DB::commit();

        return response()->json($response);
    }

    public function delete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account = BankAccount::find($input['bank_account_id']);

        $bank_account->delete();

        DB::commit();

        \Session::flash('alert-warning', 'ลบบัญชีธนาคารเรียบร้อย');

        return \redirect()->back();

    }

    public function updateAmountBot($bank_account_id) {

        $bank_account = BankAccount::whereId($bank_account_id)->whereActive(0)->first();

        if($bank_account) {

            if($bank_account->type == 9 || $bank_account->type == 10 || $bank_account->type == 11) {
                
                // $bank_account->update([
                //     'active' => 1,
                // ]);

                $api= new BotSCBPin($bank_account);

                $pin = $bank_account->pin; #pin เข้า app ดึงจาก db
        
                $deviceid = Helper::decryptString($bank_account->app_id, 1, 'base64');

                $preload = $api->preloadauth($deviceid);

                $e2ee = $api->preauth($preload['Api-Auth']);
                $e2eejson = json_decode($e2ee,true);
                $hashType = $e2eejson['e2ee']['pseudoOaepHashAlgo'];
                $Sid = $e2eejson['e2ee']['pseudoSid'];
                $ServerRandom = $e2eejson['e2ee']['pseudoRandom'];
                $pubKey = $e2eejson['e2ee']['pseudoPubKey'];

                $encryptscb = $api->encryptscb($Sid,$ServerRandom,$pubKey,$pin,$hashType);

                if(isset($encryptscb['Api-Auth'])) {

                    \Session::flash('alert-warning', 'บัญชีธนาคารขัดข้อง กรุณาติดต่อเจ้าหน้าที่ ค่ะ');

                    return \redirect()->back();

                } 

                $scblogin = $api->scblogin($preload['Api-Auth'],$deviceid,$encryptscb,$Sid);
                
                $bank_account->update([
                    'active' => 0,
                ]);

                $apiauth = $scblogin['Api-Auth'];

                $api->setBaseParam($apiauth, $bank_account->account); 

                $summary = $api->getSummary();

            } else {
                
                 // $bank_account->update([
                //     'active' => 1,
                // ]);

                $api = new BotSCBPin($bank_account);

                $pin = $bank_account->pin; #pin เข้า app ดึงจาก db
        
                $deviceid = Helper::decryptString($bank_account->app_id, 1, 'base64');

                $preload = $api->preloadauth($deviceid);

                $e2ee = $api->preauth($preload['Api-Auth']);
                $e2eejson = json_decode($e2ee,true);
                $hashType = $e2eejson['e2ee']['pseudoOaepHashAlgo'];
                $Sid = $e2eejson['e2ee']['pseudoSid'];
                $ServerRandom = $e2eejson['e2ee']['pseudoRandom'];
                $pubKey = $e2eejson['e2ee']['pseudoPubKey'];

                $encryptscb = $api->encryptscb($Sid,$ServerRandom,$pubKey,$pin,$hashType);

                if(isset($encryptscb['Api-Auth'])) {

                    \Session::flash('alert-warning', 'บัญชีธนาคารขัดข้อง กรุณาติดต่อเจ้าหน้าที่ ค่ะ');

                    return \redirect()->back();

                } 

                $scblogin = $api->scblogin($preload['Api-Auth'],$deviceid,$encryptscb,$Sid);
                
                $bank_account->update([
                    'active' => 0,
                ]);

                $apiauth = $scblogin['Api-Auth'];

                $api->setBaseParam($apiauth, $bank_account->account); 

                $summary = $api->getSummary();

            }

            $bank_account->update([
                'amount' => $summary['totalAvailableBalance'],
            ]);

            \Session::flash('alert-success', 'อัพเดทเงินในบัญชีเรียบร้อยค่ะ');

        } else {

            \Session::flash('alert-warning', 'กรุณาลองใหม่อีกครั้งค่ะ');
            
        }

        return \redirect()->back();

    }

    public function updateStatusBot(Request $request) {

        $input = $request->all();

        $brand = Brand::find($input['brand_id'])->update([
            'status_bot_deposit' => $input['status']
        ]);

    }
}
