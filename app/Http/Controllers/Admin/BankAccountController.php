<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class BankAccountController extends Controller
{
    //
    public function index() {

        $bank_accounts = BankAccount::all();

        // whereNotIn('brand_id',['25','30','36'])

        $banks = Bank::all();

        $brands = Brand::all();

        return view('admin.bank-accounts.index', compact('bank_accounts','banks','brands'));

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

        // $input['token'] = Helper::encryptString($input['token'],1,'base64');

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
}
