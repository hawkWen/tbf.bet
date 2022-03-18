<?php

namespace App\Http\Controllers\Gclub;

use Carbon\Carbon;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    //
    public function index($brand) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        $customer = Customer::find(Auth::guard('customer')->user()->id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->whereStatusBot(1)->whereType(1)->get();

        if($customer->username) {

            $response = Bot::creditGclub($brand,$customer);

        } 

        return view('gclub.members.withdraw',\compact('brand','customer','bank_accounts'));

    }

    public function store(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::find(Auth::guard('customer')->user()->id);

        $brand = Brand::find($customer->brand_id);

        $customer_withdraw_last = CustomerWithdraw::whereBrandId($brand->id)->whereCustomerId($customer->id)->orderBy('created_at','desc')->whereStatus(2)->first();

        if($customer_withdraw_last) {

            $last = Carbon::parse($customer_withdraw_last->updated_at);

            $now = Carbon::parse(date('Y-m-d H:i:s'));

            $duration = $now->diffInSeconds($last);

            if($duration <= 600) {

                return redirect()->back()->withErrors(['คุณทำรายการถอนเร็วเกินไป ทำรายการใหม่อีกครั้ง ภายในเวลา 10 นาที']);

            }

        }

        $customer_withdraw_unique = CustomerWithdraw::whereBrandId($brand->id)->whereCustomerId($customer->id)->orderBy('created_at','desc')->whereNotIn('status',[2,5])->first();

        if($customer_withdraw_unique) {

            return redirect()->back()->withErrors(['มีรายการถอนที่กำลังตรวจสอบ หรือ โอนเงิน กรุณาติดต่อเจ้าหน้าที่']);

        }

        $input['amount'] = str_replace(',','',$input['amount']);

        if($input['amount'] < $brand->withdraw_min) {
            return redirect()->back()->withErrors(['ถอนขั้นต่ำ '.$brand->withdraw_min]);
        }

        $response_credit = Bot::creditGclub($brand,$customer);

        if($input['amount'] > $customer->credit) {

            return redirect()->back()->withErrors(['เครดิตไม่พอค่ะ']);
            
        }

        $response_withdraw = Bot::withdrawGclub($brand,$customer,$input['amount']);

        if($response_withdraw['status'] === false) {
            
            return redirect()->back()->withErrors(['ลองใหม่อีกครั้งครับ']);

        }

        $promotion_cost = PromotionCost::whereBrandId($brand->id)->whereCustomerId($customer->id)->whereStatus(0)->first();

        //ติดโปร
        if($promotion_cost) {

            $input['promotion_cost_id'] = $promotion_cost->id;

            $input['promotion_id'] = $promotion_cost->promotion_id;

            $input['type_withdraw'] = 2;

            $input['status_credit'] = 1;

        } else {

            //ถอนออโต้
            if($input['amount'] <= $brand->withdraw_auto_max) {
                
                $input['status_credit'] = 1;

                $input['type_withdraw'] = 1;

            } else {

                $input['type_withdraw'] = 2;
    
                $input['status_credit'] = 1;
                
            }

        }
        //ไม่ติดโปร

        $input['brand_id'] = $customer->brand_id;

        $input['game_id'] = $customer->game_id;

        $input['customer_id'] = $customer->id;

        $input['status'] = 0;

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['username'] = $customer->username;

        $input['name'] = $customer->name;

        $customer_withdraw = CustomerWithdraw::create($input);

        DB::commit();

        if($input['type_withdraw'] == 2) {

            $message = 'พนักงานกำลังตรวจสอบการถอนเงิน จำนวน '.number_format($input['amount'],2).' ฿ ให้คุณ username: '. $customer->username.' กรุณารอ 2-3 นาที';

        } else {

            $message = 'ระบบกำลังถอนเงินจำนวน '.number_format($input['amount'],2).' ฿ ให้คุณ username: '. $customer->username.'';

        }
        
        \Session::flash('alert-success', $message);

        return \redirect()->back();

    }
}
