<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    //
    public function index(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $bank_account_transactions = BankAccountTransaction::whereIn('brand_id',$brands->pluck('id'))->orderBy('created_at','desc')->take(20)->get();

        $bank_accounts = BankAccount::whereStatusBot(1)->get();

        return view('support.monitors.index',\compact('dates','brands','bank_account_transactions','bank_accounts'));
    }
}
