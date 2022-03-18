<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\CustomerDeposit;
use App\Models\PromotionCost;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Customer;
use App\Models\BankAccountTransaction;

class MarketingController extends Controller
{
    //
    public function top(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateMarketingReport($request->get('start_date'),$request->get('end_date'));

        $customer_deposits = CustomerDeposit::selectRaw("SUM(amount) as total, DATE_FORMAT(created_at, '%d/%m/%Y') date")
            ->whereBrandId($brand->id)
            ->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])
            ->groupBy('date')
            ->get();

        $customer_withdraws = CustomerWithdraw::selectRaw("SUM(amount) as total, DATE_FORMAT(created_at, '%d/%m/%Y') date")
            ->whereBrandId($brand->id)
            ->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])
            ->groupBy('date')
            ->get();

        $promotion_costs = PromotionCost::selectRaw("SUM(bonus) as total, DATE_FORMAT(created_at, '%d/%m/%Y') date")
            ->whereBrandId($brand->id)
            ->whereBetween('created_at',[$dates['start_date'],$dates['end_date']])
            ->groupBy('date')
            ->get();

        $promotion_cost_tops = PromotionCost::whereBrandId($brand->id)
            ->select('promotion_id',DB::raw('SUM(bonus) as total_bonus'))
            ->with('promotion')
            ->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])
            ->orderBy('total_bonus','desc')
            ->groupBy('promotion_id')
            ->get();

        $query_date = collect([]);

        $query_promotion = collect([]);

        foreach($customer_deposits as $customer_deposit) {

            $query_date->push($customer_deposit->date);

        }

        foreach($promotion_cost_tops as $promotion_query) {

            $promotion_name = $promotion_query->promotion->name;

            $promotion_name = mb_strimwidth($promotion_name,'0','30','...');

            $query_promotion->push($promotion_name);

        }

        $customers = Customer::whereBrandId($brand->id)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $customer_news = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customers->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();

        $customer_deposit_tops = CustomerDeposit::whereBrandId($brand->id)->select('customer_id',DB::raw('SUM(amount) as total_deposit'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_deposit','desc')->groupBy('customer_id')->take(10)->get();

        $customer_promotion_tops = PromotionCost::whereBrandId($brand->id)->select('customer_id',DB::raw('SUM(bonus) as total_bonus'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_bonus','desc')->groupBy('customer_id')->take(10)->get();

        $customer_withdraw_tops = CustomerWithdraw::whereBrandId($brand->id)->select('customer_id',DB::raw('SUM(amount) as total_withdraw'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_withdraw','desc')->groupBy('customer_id')->take(10)->get();

        return view('agent.marketings.top', compact('brand',
            'dates',
            'customer_deposits',
            'customer_withdraws',
            'query_date',
            'promotion_costs',
            'query_promotion',
            'promotion_cost_tops',
            'customer_deposit_tops',
            'customer_promotion_tops',
            'customer_withdraw_tops',
            'customers',
            'customer_news',
        ));

    }

    public function customer(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateMarketingReport($request->get('start_date'),$request->get('end_date'));

        $customer_from_types = Customer::whereBrandId($brand->id)
            ->select('from_type',DB::raw('COUNT(*) as customers'))
            ->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])
            ->groupBy('from_type')
            ->get();

        $collect_from_types = collect([]);

        foreach($customer_from_types as $collect_from_type) {

            $collect_from_types->push($collect_from_type->from_type);

        }

        $bank_account_transactions = BankAccountTransaction::whereBrandId($brand->id)
            ->select('bank_account_id',DB::raw('COUNT(*) as count'))
            ->with('bankAccount','bankAccount.bank')
            ->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])
            ->groupBy('bank_account_id')
            ->get();

        $collect_bank_accounts = collect([]);
        
        foreach ($bank_account_transactions as $key => $bank_account_transaction) {
            # code...
            $collect_bank_accounts->push($bank_account_transaction->bankAccount->bank->name.' '.$bank_account_transaction->bankAccount->account);
        }

        return view('agent.marketings.customer', compact('brand',
            'dates',
            'collect_from_types',
            'customer_from_types',
            'collect_bank_accounts',
            'bank_account_transactions'
        ));

    }

}
