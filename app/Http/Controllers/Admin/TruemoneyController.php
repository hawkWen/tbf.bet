<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TruemoneyRequest;

class TruemoneyController extends Controller
{
    //
    public function index() {

        $brands = Brand::all();

        $bank_accounts = BankAccount::whereType(9)->whereBankId(0)->get();
        
        return view('admin.truemoney.index',\compact('bank_accounts','brands'));

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
}
