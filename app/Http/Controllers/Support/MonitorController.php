<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccountTransaction;
use App\Models\BankAccount;
use App\Models\Brand;
use App\Helpers\Helper;

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

    public function transaction() {

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $bank_account_transactions = BankAccountTransaction::whereIn('brand_id',$brands->pluck('id'))->orderBy('created_at','desc')->take(20)->get();

        return view('support.dashboard.transaction',compact('bank_account_transactions'));

    }

    public function bankAccount() {

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $bank_accounts = BankAccount::whereStatusBot(1)->get();

        return view('support.dashboard.bank-account',compact('bank_accounts'));

    }
}
