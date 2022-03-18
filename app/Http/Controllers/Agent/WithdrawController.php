<?php

namespace App\Http\Controllers\Agent;

use App\Helpers\Api;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\BotEvent;
use App\Models\Customer;
use App\Helpers\GClubApi;
use App\Helpers\RachaApi;
use App\Models\Promotion;
use App\Models\UserEvent;
use App\Helpers\BotSCBPin;
use App\Helpers\FastbetApi;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\PromotionCost;
use App\Helpers\FastbetBotApi;
use App\Models\BankAccountOtp;
use App\Models\CustomerDeposit;
use App\Models\BankAccountRefer;
use App\Models\CustomerWithdraw;
use App\Models\BankAccountHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class WithdrawController extends Controller
{
    //
    public function index() {

        $brand = Brand::find(Auth::user()->brand_id);

        return view('agent.withdraws.index',compact('brand'));

    }

    public function history(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->withTrashed()->paginate(30)->appends(request()->except('page'));

        return view('agent.withdraws.history', \compact('dates','brand','customer_withdraws'));

    }

    public function export(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->withTrashed()->get();

        return view('agent.withdraws.excel', compact('customer_withdraws'));

    }

    public function approve(Request $request) {

        $input = $request->all();

        // DB::beginTransaction();

        $customer_withdraw = CustomerWithdraw::find($input['customer_withdraw_id']);

        $customer = Customer::find($customer_withdraw->customer_id);

        $brand = Brand::find($customer->brand_id);

        $promotion = Promotion::find($input['promotion_id']);

        $amount_withdraw = $customer_withdraw->amount;

        if($promotion) {

            if(isset($input['bonus'])) {

                $bonus = str_replace(',','',$input['bonus']);

            } else {

                $bonus = 0;

            }

            if($promotion->type_promotion_cost == 2) {

                $amount_withdraw = $customer_withdraw->amount - $bonus;

            } else if($promotion->type_promotion_cost == 3) {

                $amount_withdraw = $customer_withdraw->amount + $bonus;

                $bonus_message = 'ได้รับโบนัสเพิ่ม '.$bonus.' จาก โปรโมชั่น '.$promotion->name;

            } else {

                $bonus_message = '';

                $amount_withdraw = $customer_withdraw->amount;

            }

        } else {

            $bonus_message = '';

            $bonus = 0;

            $amount_withdraw = $customer_withdraw->amount;

        } 

        if($amount_withdraw > 0) {
            
            $bank_account = $brand->bankAccounts->whereIn('type',[0,3,10,11])->where('bank_id','=',1)->where('status_bot','=',1)->first();

            if($bank_account) {

                if($bank_account->type == 0 || $bank_account->type == 3) {
            
                    // $bank_account->update([
                    //     'active' => 1,
                    // ]);

                    $api = new BotSCB($bank_account);
        
                    $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');
            
                    $token = Helper::decryptString($bank_account->token, 1, 'base64');
        
                    $api->setLogin($app_id, $token);
                    
                    $api->login();
            
                    $bank_account->update([
                        'active' => 0,
                    ]);
        
                    $api->setAccountNumber($bank_account->account);
        
                    $transfer = $api->Transfer($customer->bank_account,$customer->bank->code_scb,$amount_withdraw); // เลขบัญชี รหัสธนาคาร จำนวนเงิน
        
                    if($transfer['status'] === false) {
        
                        return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);
        
                    }
        
                } else if ($bank_account->type == 9 || $bank_account->type == 10 || $bank_account->type == 11) {
            
                    // $bank_account->update([
                    //     'active' => 1,
                    // ]);
        
                    $api= new BotSCBPin($bank_account);
        
                    $pin = $bank_account->pin; #pin เข้า app ดึงจาก db
            
                    $deviceid = Helper::decryptString($bank_account->app_id, 1, 'base64');
        
                    $preload = $api->preloadauth($deviceid);
                    $e2ee = $api->preauth($preload['Api-Auth']);
                    $e2eejson = json_decode($e2ee,true);
                    $hashType = $e2eejson['e2ee']['pseudoOaepHashAlgo'];
                    $Sid = $e2eejson['e2ee']['pseudoSid'];
                    $ServerRandom = $e2eejson['e2ee']['pseudoRandom'];
                    $pubKey = $e2eejson['e2ee']['pseudoPubKey'];
        
                    $encryptscb = $api->encryptscb($Sid,$ServerRandom,$pubKey,$pin,$hashType);
                    $scblogin = $api->scblogin($preload['Api-Auth'],$deviceid,$encryptscb,$Sid);
                    $apiauth = $scblogin['Api-Auth'];
        
                    $api->setBaseParam($apiauth, $bank_account->account); 
        
                    $transfer = $api->Transfer($customer->bank_account,$customer->bank->code_scb,$amount_withdraw); // เลขบัญชี รหัสธนาคาร จำนวนเงิน
        
                    if($transfer['status'] === false) {
        
                        return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);
        
                    }
            
                    $bank_account->update([
                        'active' => 0,
                    ]);
                    
                }

                // dd($transfer);

                DB::beginTransaction();

                if($transfer['status'] == 1) {

                    $customer_withdraw->update([
                        'status' => 2,
                        'bank_account_id' => $bank_account->id,
                        'user_id' => Auth::user()->id,
                        'remark' => json_encode($transfer),
                    ]);

                    BankAccountHistory::create([
                        'brand_id' => $brand->id,
                        'bank_account_id' => $bank_account->id,
                        'user_id' => 0,
                        'table_id' => $customer_withdraw->id,
                        'table' => 'customer_withdraws',
                        'amount' => $amount_withdraw,
                        'type' => 2,
                    ]);

                    $bank_account->decrement('amount', $amount_withdraw);

                    DB::commit();

                } else {

                    $customer_withdraw->update([
                        'remark' => json_encode($transfer),
                    ]);

                    DB::commit();

                    return redirect()->back()->withErrors([$transfer['msg']]);

                }

            } else {

                $customer_withdraw->update([
                    'remark' => 'ธนาคารปิด',
                ]);

                return redirect()->back()->withErrors(['ธนาคารปิด']);

            }

        }

        UserEvent::create([
            'brand_id' => $brand->id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน '.Auth::user()->name.' ได้ตัดเครดิต '.$customer->name.' เป็นจำนวน '.$amount_withdraw.' เครดิต '
        ]);

        $bonus_message = '';

        if($customer_withdraw->promotionCost) {

            $customer_withdraw->promotionCost->update([
                'status' => 1,
            ]);

            PromotionCost::find($customer_withdraw->promotion_cost_id)->update([
                'bonus' => 0,
                'status' => 2,
            ]);

            $bonus_message = 'ดึงโบนัสคืน '.$bonus.' จาก โปรโมชั่น '.$customer_withdraw->promotionCost->promotion->name;

        }

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $message = 'ระบบได้ถอนเงินให้กับ '.$customer->username.' จำนวน '.$amount_withdraw.' เรียบร้อยแล้วค่ะ ขอบคุณค่ะ';

        $message .= $bonus_message;

        $push = $line_api->pushMessage($customer->line_user_id, $message);

        $customer_withdraw->update([
            'amount' => $amount_withdraw,
        ]);

        // DB::commit();

        \Session::flash('alert-success', 'อนุมัติการถอนเงินเรียบร้อยแล้ว Username: '. $customer->username);

        return \redirect()->back();

    }

    public function cancel(Request $request) {

        $input = $request->all();

        $customer_withdraw = CustomerWithdraw::find($input['customer_withdraw_id']);

        $username = $customer_withdraw->username;

        $brand = Brand::find($customer_withdraw->brand_id);

        $customer = Customer::find($customer_withdraw->customer_id);

        if($customer_withdraw->status_credit == 1) {

            $total_amount = $customer_withdraw->amount;

            $api = new Api($brand);

            $data['username'] = $customer->username;

            $data['amount'] = $total_amount;

            $data['customer_id'] = $customer->id;

            if($brand->game_id == 1) {

                $data['agent_order'] = $customer->agent_order;

            }

            $api_deposit = $api->deposit($data);

            if($brand->game_id == 1) {

                if($api_deposit['data']['online'] === true && $api_deposit['status'] === false) {
                    //customer online
                    $bank_account_transaction->update([
                        'status' => 5,
                    ]);

                    return abort(500, 'API ERROR TRY AGAIN');

                } else if ($api_deposit['data']['online'] === false && $api_deposit['status'] === false) {
                    //api error
                    $bank_account_transaction->update([
                        'status' => 0,
                    ]);

                    return abort(500, 'API ERROR TRY AGAIN');

                }

            } else {

                if($api_deposit['status'] === false) {

                    $bank_account_transaction->update([
                        'status' => 0,
                    ]);

                    return abort(500, 'API ERROR TRY AGAIN');

                }

            }

        }

        DB::beginTransaction();

        $customer_withdraw->update([
            'status' => 5,
            'remark' => $input['remark']
        ]);

        $customer_withdraw->delete();

        DB::commit();

        \Session::flash('alert-danger', 'ยกเลิกการถอนเงินให้กับ username: '. $username);

        return \redirect()->back();

    }

    public function refresh(Request $request) {

        $input = $request->all();

        $customer_withdraw = CustomerWithdraw::find($input['customer_withdraw_id']);

        $customer = Customer::find($customer_withdraw->customer_id);

        $brand = Brand::find($customer->brand_id);

        $bank_account = $brand->bankAccounts->whereIn('type',[0,3,10,11])->where('bank_id','=',1)->where('status_bot','=',1)->first();

        if($bank_account) {

            if($bank_account->type == 0 || $bank_account->type == 3) {
            
                // $bank_account->update([
                //     'active' => 1,
                // ]);

                $api = new BotSCB($bank_account);
    
                $app_id = Helper::decryptString($bank_account->app_id, 1, 'base64');
        
                $token = Helper::decryptString($bank_account->token, 1, 'base64');
    
                $api->setLogin($app_id, $token);
                
                $api->login();
            
                $bank_account->update([
                    'active' => 0,
                ]);
    
                $api->setAccountNumber($bank_account->account);
    
                $transfer = $api->Transfer($customer->bank_account,$customer->bank->code_scb,$customer_withdraw->amount_withdraw); // เลขบัญชี รหัสธนาคาร จำนวนเงิน
    
                if($transfer['status'] === false) {
    
                    return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);
    
                }
    
            } else if ($bank_account->type == 9 || $bank_account->type == 10 || $bank_account->type == 11) {
            
                // $bank_account->update([
                //     'active' => 1,
                // ]);
    
                $api= new BotSCBPin($bank_account);
    
                $pin = $bank_account->pin; #pin เข้า app ดึงจาก db
        
                $deviceid = Helper::decryptString($bank_account->app_id, 1, 'base64');
    
                $preload = $api->preloadauth($deviceid);
                $e2ee = $api->preauth($preload['Api-Auth']);
                $e2eejson = json_decode($e2ee,true);
                $hashType = $e2eejson['e2ee']['pseudoOaepHashAlgo'];
                $Sid = $e2eejson['e2ee']['pseudoSid'];
                $ServerRandom = $e2eejson['e2ee']['pseudoRandom'];
                $pubKey = $e2eejson['e2ee']['pseudoPubKey'];
    
                $encryptscb = $api->encryptscb($Sid,$ServerRandom,$pubKey,$pin,$hashType);
                $scblogin = $api->scblogin($preload['Api-Auth'],$deviceid,$encryptscb,$Sid);
                $apiauth = $scblogin['Api-Auth'];
    
                $api->setBaseParam($apiauth, $bank_account->account); 
    
                $transfer = $api->Transfer($customer->bank_account,$customer->bank->code_scb,$customer_withdraw->amount_withdraw); // เลขบัญชี รหัสธนาคาร จำนวนเงิน
    
                if($transfer['status'] === false) {
    
                    return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);
    
                }
            
                $bank_account->update([
                    'active' => 0,
                ]);
                
            }

            DB::beginTransaction();

            if($transfer['status'] == 1) {

                $customer_withdraw->update([
                    'status' => 2,
                    'bank_account_id' => $bank_account->id,
                    'user_id' => Auth::user()->id,
                    'remark' => json_encode($transfer),
                ]);

                BankAccountHistory::create([
                    'brand_id' => $brand->id,
                    'bank_account_id' => $bank_account->id,
                    'user_id' => 0,
                    'table_id' => $customer_withdraw->id,
                    'table' => 'customer_withdraws',
                    'amount' => $amount_withdraw,
                    'type' => 2,
                ]);

                $bank_account->decrement('amount', $amount_withdraw);

                DB::commit();

            } else {

                $customer_withdraw->update([
                    'remark' => json_encode($transfer),
                ]);

                DB::commit();

                return redirect()->back()->withErrors([$transfer['msg']]);

            }

        } else {

            $customer_withdraw->update([
                'remark' => 'ธนาคารปิด',
            ]);

            return redirect()->back()->withErrors(['ธนาคารปิด']);

        }

        \Session::flash('alert-success', 'บอทได้ถอนเงินให้กับ username: '. $customer->username.' เรียบร้อย');

        return \redirect()->back();

    }

    public function manual(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer_withdraw = CustomerWithdraw::find($input['customer_withdraw_id']);

        $customer = Customer::find($customer_withdraw->customer_id);

        $brand = Brand::find($customer->brand_id);

        // Check Promotion
        if($customer_withdraw->promotionCost) {

            $customer_withdraw->promotionCost->update([
                'status' => 1,
            ]);

        }

        $bank_account = BankAccount::find($input['bank_account_id']);

        $customer_withdraw->update([
            'bank_account_id' => $bank_account->id,
            'status' => 2,
            'user_id' => Auth::user()->id,
            'remark' => 'พนักงาน '.Auth::user()->name.' ได้ถอนเงินให้กับ '.$customer->name.' เป็นจำนวน '.$customer_withdraw->amount.' บาท ',
        ]);

        BankAccountHistory::create([
            'brand_id' => $brand->id,
            'bank_account_id' => $bank_account->id,
            'user_id' => 0,
            'table_id' => $customer_withdraw->id,
            'table' => 'customer_withdraws',
            'amount' => $customer_withdraw->amount,
            'type' => 2,
        ]);

        $bank_account->decrement('amount', $customer_withdraw->amount);

        UserEvent::create([
            'brand_id' => $brand->id,
            'user_id' => Auth::user()->id,
            'description' => 'พนักงาน '.Auth::user()->name.' ได้ถอนเงินให้กับ '.$customer->name.' เป็นจำนวน '.$customer_withdraw->amount.' บาท '
        ]);

        DB::commit();

        \Session::flash('alert-success', 'บอทได้ถอนเงินให้กับ username: '. $customer->username.' เรียบร้อย');

        return redirect()->back();
    }

    public function lists() {

        $dates = Helper::getTimeMonitor();

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->withTrashed()->get();

        return view('agent.withdraws.list', \compact('bank_accounts','brand','customer_withdraws'));

    }

    public function notify() {

        $brand = Brand::find(Auth::user()->brand_id);

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereNotIn('status',[2,5])->count();

        return response()->json([
            'count' => $customer_withdraws,
            'brand' => $brand
        ]);

    }

    public function botWithdrawSCBEasy($brand,$bank_account,$customer,$customer_withdraw) {

        $scb_id = $bank_account->username;

        $scb_pass = $bank_account->password;

        $mobile_otp = 0;

        $bank_account_id = $bank_account->id;

        $bank_account = $bank_account->account;

        $customer_bank_account = $customer->bank_account;

        $customer_bank_code = $customer->bank->code_scb;

        $customer_amount = $customer_withdraw;
            
        include('scb.withdraw.php');

        $send = get_otp_withdraw($scb_id,$scb_pass,$customer_amount,$customer_bank_account,$customer_bank_code,$mobile_otp,$bank_account);

        // dd($scb_id,$scb_pass,$customer_amount,$customer_bank_account,$customer_bank_code,$mobile_otp,$bank_account,$send);

        $result = [
            'status' => false,
            'msg' => null
        ];
        
        if($send['status']==1){

            $bank_account_refer = BankAccountRefer::create([
                'bank_account_id' => $bank_account_id,
                'refer' => $send['refer'],
            ]);

            $ii=0;

            $checktime=date("YmdHis");

            $loop=5;

            while($ii<$loop){
            
                $ii++;
            
                sleep(10);

                $bank_account_otp = BankAccountOtp::whereRefer($bank_account_refer->refer)->whereStatus(0)->first();

                if($bank_account_otp['otp'] != null) {
                    $confirm_data=confirm_otp($bank_account_otp['otp'],$send['__VIEWSTATE'],$send['__VIEWSTATEGENERATOR'],$customer_bank_code,$bank_account);
                    if($confirm_data['status']==1){
                        $bank_account_otp->update([
                            'status' => 1,
                        ]);
                        $result['status'] = true;
                        $result['msg'] = 'โอนสำเร็จ';
                    }else{
                        $bank_account_otp->update([
                            'status' => 1,
                        ]);
                        $result['status'] = false;
                        $result['msg'] = $send['detail'];
                    }
                    $ii=$loop;
                    $otpin=true;
                }
                
            } 

            if(!$otpin){
                $result['status'] = false;
                $result['msg'] = 'รอ otp นานเกินไป otp มาไม่ถึง';
            }

        } else{
            $result['status'] = false;
            $result['msg'] = $send['detail'];

        }
        return $result;

    }
}
