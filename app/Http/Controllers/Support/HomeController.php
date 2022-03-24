<?php

namespace App\Http\Controllers\Support;

use App\User;
use App\Helpers\Api;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Annoucement;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Models\BankAccountScb;
use App\Models\CustomerDeposit;
use App\Models\BrandBankAccount;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\BotLog;

class HomeController extends Controller
{
    //
    public function index(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::all();

        $brand_select = ($request->get('brand_id')) ? $request->get('brand_id') : 0;

        $customers = Customer::whereBrandId($brand_select)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $customer_news = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customers->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();

        $customer_deposits = CustomerDeposit::whereBrandId($brand_select)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand_select)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $promotion_costs = PromotionCost::whereBrandId($brand_select)->whereIn('status',[0,1])->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();
        
        $group_by_promotion_costs = PromotionCost::with('promotion')->select('promotion_id',DB::raw('SUM(bonus) as bonus'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->whereBrandId($brand_select)->groupBy('promotion_id')->get();

        $bank_accounts = BankAccount::whereBrandId($brand_select)->orderBy('type')->get();

        $customer_all = Customer::whereBrandId($brand_select)->get();

        $customer_active = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customer_all->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();

        $annoucements = Annoucement::orderBy('created_at','desc')->take(2)->get();

        $customer_deposit_tops = CustomerDeposit::whereBrandId($brand_select)->select('customer_id',DB::raw('SUM(amount) as total_deposit'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_deposit','desc')->groupBy('customer_id')->take(5)->get();

        $customer_promotion_tops = PromotionCost::whereBrandId($brand_select)->select('customer_id',DB::raw('SUM(bonus) as total_bonus'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_bonus','desc')->groupBy('customer_id')->take(5)->get();

        $customer_withdraw_tops = CustomerWithdraw::whereBrandId($brand_select)->select('customer_id',DB::raw('SUM(amount) as total_withdraw'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_withdraw','desc')->groupBy('customer_id')->take(5)->get();

        return view('support.home',compact('brands','brand_select','dates','customers','customer_deposits','customer_withdraws','promotion_costs','bank_accounts','customers','customer_news','group_by_promotion_costs','customer_all','customer_active','annoucements','customer_deposit_tops','customer_withdraw_tops','customer_promotion_tops'));

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

    public function logs() {

        $bot_logs = BotLog::orderBy('created_at','desc')->paginate(50);

        return view('support.logs', compact('bot_logs'));
    }
}
