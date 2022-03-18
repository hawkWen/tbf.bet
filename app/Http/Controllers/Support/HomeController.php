<?php

namespace App\Http\Controllers\Support;

use App\Models\Brand;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BankAccountTransaction;

class HomeController extends Controller
{
    //
    public function index(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $bank_account_transactions = BankAccountTransaction::whereIn('brand_id',$brands->pluck('id'))->orderBy('created_at','desc')->take(20)->get();

        // dd($bank_account_transactions);

        return view('support.home',\compact('dates','brands','bank_account_transactions'));

    }

    public function changePassword(Request $request) {
        
        $input = $request->all();

        DB::beginTransaction();

        $admin = User::find(auth()->user()->id);

        if (!Hash::check($input['password_old'], $admin->password)) {
            // The passwords match...
            return \redirect()->back()->withErrors('รหัสผ่านเดิมไม่ถูกต้อง');
        }

        $admin->update([
            'password' => \bcrypt($input['password'])
        ]);

        DB::commit();

        \Session::flash('alert-success', 'เปลี่ยนรหัสผ่านสำเร็จ');

        return \redirect()->back();

    }

    public function transaction() {

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $bank_account_transactions = BankAccountTransaction::whereIn('brand_id',$brands->pluck('id'))->orderBy('created_at','desc')->take(20)->get();

        return view('support.dashboard.transaction',compact('bank_account_transactions'));

    }
}
