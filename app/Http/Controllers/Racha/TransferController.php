<?php

namespace App\Http\Controllers\Gclub;

use Carbon\Carbon;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Helpers\GClubApi;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Helpers\FastbetApi;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Models\CustomerDeposit;
use App\Models\CustomerWithdraw;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TransferController extends Controller
{
    //
    public function index($brand) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        return view('gclub.transfer',\compact('brand'));

    }

    public function deposit(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereLineUserId($input['line_user_id'])->first();

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

    public function withdraw(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereLineUserId($input['line_user_id'])->first();

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

        $customer_withdraw_unique = CustomerWithdraw::whereBrandId($brand->id)->whereCustomerId($customer->id)->orderBy('created_at','desc')->where('status','!=',2)->first();

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

        $bank_account = $brand->bankAccounts->whereIn('type',[0,3])->where('bank_id','=',1)->where('status_bot','=',1)->first();

        if(!$bank_account) {

            $customer_withdraw->update([
                'status' => 3,
                'type_withdraw' => 2,
            ]);
            
        }

        DB::commit();

        if($input['type_withdraw'] == 2) {

            $message = 'พนักงานกำลังตรวจสอบการถอนเงิน จำนวน '.number_format($input['amount'],2).' ฿ ให้คุณ username: '. $customer->username.' กรุณารอ 2-3 นาที';

        } else {

            $message = 'ระบบกำลังถอนเงินจำนวน '.number_format($input['amount'],2).' ฿ ให้คุณ username: '. $customer->username.'';

        }
        
        \Session::flash('alert-success', $message);

        return \redirect()->back();

    }

    public function view($brand,$line_user_id) {
    
        $brand = Brand::whereSubdomain($brand)->first();

        $customer = Customer::whereBrandId($brand->id)->whereLineUserId($line_user_id)->first();

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
        
        $bank_account_reserves = BankAccount::whereBrandId($brand->id)->whereType(2)->get();

        Bot::creditGclub($brand,$customer);

        return view('gclub.transfer-view', compact('customer','brand','bank_accounts','promotions','bank_account_reserves'));

    }
}
