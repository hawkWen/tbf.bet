<?php

namespace App\Http\Controllers\Super;

use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerDeposit;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    //
    public function customer(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $reports = collect([]);

        foreach($brands as $brand) {

            $customers = Customer::whereBrandId($brand->id)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

            $customer_news = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customers->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();
            
            $reports->push([
                'brand' => $brand->name,
                'customers' => $customers->count(),
                'customer_news' => $customer_news->count(),
            ]);

        }

        return view('super.reports.customer', compact('dates','reports'));

    }

    public function customerExcel(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $reports = collect([]);

        foreach($brands as $brand) {

            $customers = Customer::whereBrandId($brand->id)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

            $customer_news = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customers->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();
            
            $reports->push([
                'brand' => $brand->name,
                'customers' => $customer_deposits->count(),
                'customer_news' => $customer_deposits->count(),
            ]);

        }

        return view('super.reports.customer-excel', compact('reports'));

    }

    public function deposit(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $reports = collect([]);

        foreach($brands as $brand) {

            $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereStatus(1)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->get();

            $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereStatus(2)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->get();
            
            $reports->push([
                'brand' => $brand->name,
                'deposit' => $customer_deposits->sum('amount'),
                'deposit_count' => $customer_deposits->count(),
                'withdraw' => $customer_withdraws->sum('amount'),
                'withdraw_count' => $customer_deposits->count(),
            ]);

        }

        return view('super.reports.deposit', compact('dates','reports'));

    }

    public function depositExport(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brands = Brand::whereIn('type_api',[1,2])->get();

        $reports = collect([]);

        foreach($brands as $brand) {

            $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereStatus(1)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->get();

            $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereStatus(2)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->get();
            
            $reports->push([
                'brand' => $brand->name,
                'deposit' => $customer_deposits->sum('amount'),
                'deposit_count' => $customer_deposits->count(),
                'withdraw' => $customer_withdraws->sum('amount'),
                'withdraw_count' => $customer_deposits->count(),
            ]);

        }

        return view('super.reports.deposit-excel', compact('dates','reports'));

    }

    public function withdraw(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->orderBy('created_at','desc')->withTrashed()->paginate(30)->appends(request()->except('page'));

        return view('super.reports.withdraw', compact('brand','dates','customer_withdraws'));

    }

    public function withdrawExcel(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->orderBy('created_at','desc')->withTrashed()->get();

        return view('super.reports.withdraw-excel', compact('brand','dates','customer_withdraws'));

    }
}
