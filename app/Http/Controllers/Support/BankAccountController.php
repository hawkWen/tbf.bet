<?php

namespace App\Http\Controllers\Support;

use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\BankAccountScb;
use App\Helpers\BotSCBPin;
use App\Helpers\BotSCB;

class BankAccountController extends Controller
{
    //
    public function index() {

        $bank_accounts = BankAccount::whereBankId(1)->get();

        $banks = Bank::all();

        $brands = Brand::whereIn('type_api',['1','2'])->get();

        $bank_account_scbs = BankAccountScb::orderBy('created_at','desc')->get();

        return view('support.bank-accounts.index', compact('bank_accounts','banks','brands','bank_account_scbs'));

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $input['app_id'] = Helper::encryptString($input['app_id'],1,'base64');
                
        $input['token'] = Helper::encryptString($input['token'],1,'base64');

        BankAccount::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มบัญชีธนาคารสำเร็จ');

        return redirect()->back();

    }

    public function update(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $input['app_id'] = Helper::encryptString($input['app_id'],1,'base64');
                
        $input['token'] = Helper::encryptString($input['token'],1,'base64');

        // $api->setLogin($app_id, $token);

        $bank_account = BankAccount::find($input['bank_account_id']);

        $bank_account->update($input);

        DB::commit();

        \Session::flash('alert-success', 'แก้ไขบัญชีธนาคารเรียบร้อย');

        return \redirect()->back();

    }

    public function updateStatus(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        BankAccount::find($input['bank_account_id'])->update([
            $input['type'] => $input['status']
        ]);

        DB::commit();
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

    public function check($bank_account_id) {

        $bank_account = BankAccount::whereId($bank_account_id)->whereActive(0)->first();

        if($bank_account) {

            if($bank_account->type == 9 || $bank_account->type == 10 || $bank_account->type == 11) {

                $api = new BotSCBPin($bank_account);
                
                $bank_account->update([
                    'active' => 0,
                ]);

                $api->setBaseParam(); 

                $summary = $api->getSummary();

            } else {

                $api = new BotSCB($bank_account);
        
                $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');
        
                $token = Helper::decryptString($bank_account->token, 1, 'base64');
        
                $api->setLogin($app_id, $token);
        
                $api->login();
                
                $bank_account->update([
                    'active' => 0,
                ]);
        
                $api->setAccountNumber($bank_account->account);
        
                $summary = $api->getSummary();
            }

            if(isset($summary['totalAvailableBalance'])) {

                \Session::flash('alert-success', 'บัญชีธนาคารสถานะปกติ');

                $bank_account->update([
                    'amount' => $summary['totalAvailableBalance'],
                ]);

            } else {

                \Session::flash('alert-warning', 'บัญชีธนาคารมีขัดข้อง กรุณาติดต่อเจ้าหน้าที่ ค่ะ');

            }

        } else {

            \Session::flash('alert-warning', 'กรุณาลองใหม่อีกครั้งค่ะ');
            
        }

        return \redirect()->back();

    }
}
