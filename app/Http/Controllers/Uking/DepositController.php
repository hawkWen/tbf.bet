<?php

namespace App\Http\Controllers\Uking;

use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Models\Promotion;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\CustomerDeposit;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    //
    public function index($brand) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        $customer = Customer::find(Auth::guard('customer')->user()->id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->whereStatusBot(1)->whereIn('type',[0,1])->get();

        $promotion_queries = Promotion::whereBrandId($brand->id)->get();

        $promotions = collect([]);

        foreach($promotion_queries as $promotion_query) {

            if ($promotion_query->type_promotion == 1) {

                $customer_deposits = CustomerDeposit::whereCustomerId($customer->id)->wherePromotionId($promotion_query->id)->whereBetween('created_at', [date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])->get();

                if($customer_deposits->count() == 0) {

                    $promotions->push($promotion_query);

                }

            } else if($promotion_query->type_promotion == 3) {

                $customer_deposits = CustomerDeposit::whereCustomerId($customer->id)->wherePromotionId($promotion_query->id)->get();

                if($customer_deposits->count() == 0) {

                    $promotions->push($promotion_query);
                    
                }

            } else if($promotion_query->type_promotion == 2) {

                $promotions->push($promotion_query); 
 
             }

        }

        //check promotion condition
        
        $bank_account_reserve = BankAccount::whereBrandId($brand->id)->whereType(2)->first();

        if($brand->type_deposit == 1) {
            return view('uking.members.deposit', compact('customer','brand','bank_accounts','promotions','bank_account_reserve'));
        } else {
            return view('uking.members.deposit-fast', compact('customer','brand','bank_accounts','promotions','bank_account_reserve'));
        }

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find(Auth::guard('customer')->user()->id);

        if($input['slip'] !== 'null') {
            
            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            // return response()->json($storage);

            if(env('APP_ENV') == 'local') {

                $input['slip_url'] = Storage::url($storage);

            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));

            }

            $input['slip'] = $storage;

        } 

        $input['brand_id'] = $customer->brand_id;

        $input['game_id'] = $customer->game_id;

        $input['customer_id'] = $customer->id;

        $input['status'] = 0;

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['username'] = $customer->username;

        $input['name'] = $customer->name;

        if($input['promotion_id'] != 0) {

            //promotion
            $promotion = Promotion::find($input['promotion_id']);

            $input['bonus'] = Helper::bonusCalculator($input['amount'],$promotion);

        } else {

            $input['bonus'] = 0;

        }

        CustomerDeposit::create($input);

        DB::commit();

        \Session::flash('alert-success', 'รอพนักงานตรวจสอบสลิปในการเติมเงินซักครู่นะคะ ขอบคุณค่ะ');

        return \redirect()->back();
    }
}
