<?php

namespace App\Http\Controllers\Support;

use App\Models\Brand;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TruemoneyRequest;
use App\Helpers\TrueMoney;

class TruemoneyController extends Controller
{
    //
    public function index() {

        $brands = Brand::whereIn('type_api',['1','2'])->get();

        $bank_accounts = BankAccount::whereBankId(0)->whereType(9)->get();
        
        return view('support.truemoney.index',\compact('bank_accounts','brands'));

    }

    public function store(Request $request) {

        $input = $request->all();

        $input['bank_id'] = 0;

        $input['type'] = 9;

        DB::beginTransaction();

        BankAccount::create($input);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มบัญชีธนาคารสำเร็จ');

        return redirect()->back();

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

        try {
            //code...
            $bank_account = BankAccount::find($bank_account_id);

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

            \Session::flash('alert-warning', 'ทรูมันนี่ปกติค่ะ ยอดเงินคงเหลือ '. $balance);

            return redirect()->back();

        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withErrors('บัญชีทรูมันนี่มีปัญหา กรุณาต่ออายุที่ tmn.one');
        }

    }
}
