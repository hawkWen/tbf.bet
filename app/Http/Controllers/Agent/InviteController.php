<?php

namespace App\Http\Controllers\Agent;

use Carbon\Carbon;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\CustomerDeposit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    //
    public function index() {

        $brand = Brand::find(Auth::user()->brand_id);

        $agent = Customer::whereBrandId($brand->id)->select('invite_id')->groupBy('invite_id')->get();

        $agents = Customer::whereIn('id', $agent->pluck('invite_id'))->get();

        return view('agent.customers.invite', compact('brand','agents'));

    }

    public function show($agent_id) {

        $brand = Brand::find(Auth::user()->brand_id);

        $agent = Customer::find($agent_id);

        $promotions = Promotion::whereBrandId($brand->id)->whereTypePromotion(5)->get();

        $customer_actives = collect([]);

        $month = '';

        $name = '';

        // if(isset($_GET['month'])) {

        //     $month = $_GET['month'];

        //     $month = $month;

        //     $start = Carbon::createFromFormat('Y-m',date('Y').'-'.$month)->startOfMonth();

        //     $end = Carbon::createFromFormat('Y-m',date('Y').'-'.$month)->endOfMonth();

        //     $customer_invite = Customer::whereInviteId($agent_id)->get();

        //     $customer_deposit = new CustomerDeposit();

        //     $customer_deposit = $customer_deposit->select('customer_id', DB::raw('count(*) as total,sum(amount) as total_amount'));

        //     $customer_deposit = $customer_deposit->with('customer');

        //     $customer_deposit = $customer_deposit->whereIn('customer_id', $customer_invite->pluck('id'));

        //     $customer_deposit = $customer_deposit->whereBetween('created_at',[$start, $end]);

        //     if($_GET['name']) {

        //         $name = $_GET['name'];

        //         $customer_deposit = $customer_deposit->where('name','like','%'.$_GET['name'].'%');

        //     }

        //     $customer_deposits = $customer_deposit->groupBy('customer_id')->get();

        //     foreach($customer_deposits as $customer_deposit) {

        //         $customer = $customer_deposit->customer;

        //         if($customer) {
 
        //             $customer_actives->push([
        //                 'customer_id' => $customer->id,
        //                 'username' => $customer->username,
        //                 'name' => $customer->name,
        //                 'total' => $customer_deposit->total,
        //                 'total_amount' => $customer_deposit->total_amount,
        //             ]);

        //         }

        //     }

        // } else {


        //     $customer_invite = Customer::whereInviteId($agent_id)->get();

        //     $customer_deposit = new CustomerDeposit();

        //     $customer_deposit = $customer_deposit->select('customer_id', DB::raw('count(*) as total,sum(amount) as total_amount'));

        //     $customer_deposit = $customer_deposit->with('customer');

        //     $customer_deposit = $customer_deposit->whereIn('customer_id', $customer_invite->pluck('id'));

        //     $customer_deposits = $customer_deposit->groupBy('customer_id')->get();

        //     foreach($customer_deposits as $customer_deposit) {

        //         $customer = $customer_deposit->customer;

        //         if($customer) {
 
        //             $customer_actives->push([
        //                 'customer_id' => $customer->id,
        //                 'username' => $customer->username,
        //                 'name' => $customer->name,
        //                 'total' => $customer_deposit->total,
        //                 'total_amount' => $customer_deposit->total_amount,
        //             ]);

        //         }

        //     }


        // }

        return view('agent.customers.invite-show', compact('brand','agent','promotions','month','name','customer_actives'));

    }
}
