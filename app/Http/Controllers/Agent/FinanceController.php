<?php

namespace App\Http\Controllers\Agent;

use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\BotSCB;
use App\Helpers\Helper;
use App\Models\BotEvent;
use App\Helpers\BotSCBPin;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\BankAccountOtp;
use App\Models\BankAccountRefer;
use App\Models\BankAccountReturn;
use App\Models\BankAccountHistory;
use App\Models\BankAccountReceive;
use Illuminate\Support\Facades\DB;
use App\Models\BankAccountTransfer;
use App\Models\BankAccountWithdraw;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class FinanceController extends Controller
{
    //
    public function transfer(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        $bank_account_transfers = BankAccountTransfer::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->paginate(30)->appends(request()->except('page'));

        return view('agent.finances.transfer',\compact('brand','bank_accounts','bank_account_transfers','dates'));

    }

    public function transferStore(Request $request) {

        $input = $request->all();

        // DB::beginTransaction();

        $bank_account_to = BankAccount::find($input['bank_account_to_id']);

        if($bank_account_to->active == 1) {

            return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ ลองใหม่อีกครั้งค่ะ '. $transfer['msg']]);

        }

        $bank_account_from = BankAccount::find($input['bank_account_from_id']);

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['brand_id'] = Auth::user()->brand_id;
        
        $input['user_id'] = Auth::user()->id;

        $brand = Brand::find($input['brand_id']);

        if($bank_account_to->type == 0 || $bank_account_to->type == 1 || $bank_account_to->type == 3) {
            
            // $bank_account_to->update([
            //     'active' => 1,
            // ]);

            $api = new BotSCB($bank_account_to);

            $app_id = Helper::decryptString($bank_account_to->app_id, 1, 'base64');
    
            $token = Helper::decryptString($bank_account_to->token, 1, 'base64');

            $api->setLogin($app_id, $token);
            
            $api->login();
            
            $bank_account_to->update([
                'active' => 0,
            ]);

            $api->setAccountNumber($bank_account_to->account);

            $transfer = $api->Transfer($bank_account_from->account,$bank_account_from->bank->code_scb,$input['amount']); // เลขบัญชี รหัสธนาคาร จำนวนเงิน

            if($transfer['status'] === false) {

                return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);

            }

        } else if($bank_account_to->type == 6) {

            //SCBWithdrawSMS;
            $result_withdraw = $this->botWithdrawSCBEasy($brand,$bank_account_to,$bank_account_from->bank_id,$bank_account_from->account,$input['amount']);
            
            if($result_withdraw['status'] === false) {
                
                return redirect()->back()->withErrors([$result_withdraw['msg']]);

            }

        } else if ($bank_account_to->type == 9 || $bank_account_to->type == 10 || $bank_account_to->type == 11) {
            
            // $bank_account_to->update([
            //     'active' => 1,
            // ]);

            $api= new BotSCBPin($bank_account_to);

            $pin = $bank_account_to->pin; #pin เข้า app ดึงจาก db
    
            $deviceid = Helper::decryptString($bank_account_to->app_id, 1, 'base64');

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

            $api->setBaseParam($apiauth, $bank_account_to->account); 

            $transfer = $api->transfer($bank_account_from->account,$bank_account_from->bank->code_scb,$input['amount']); // เลขบัญชี รหัสธนาคาร จำนวนเงิน

            if($transfer['status'] === false) {

                return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);

            }
            
            $bank_account_to->update([
                'active' => 0,
            ]);
            
        }

        $bank_account_to->decrement('amount', $input['amount']);

        $bank_account_from->increment('amount', $input['amount']);

        if(isset($input['slip'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            if(env('APP_EN˜V') == 'local') {

                $input['slip_url'] = Storage::url($storage);

            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));

            }

            $input['slip'] = $storage;

        } else {

            $input['slip_url'] = 'https://via.placeholder.com/150';

            $input['slip'] = '';

        }

        $bank_account_transfer = BankAccountTransfer::create($input);

        BankAccountHistory::create([
            'brand_id' => $input['brand_id'],
            'bank_account_id' => $input['bank_account_to_id'],
            'user_id' => $input['user_id'],
            'table_id' => $bank_account_transfer->id,
            'table' => 'bank_account_transfers',
            'amount' => $input['amount'],
            'type' => 2,
        ]);

        BankAccountHistory::create([
            'brand_id' => $input['brand_id'],
            'bank_account_id' => $input['bank_account_from_id'],
            'user_id' => $input['user_id'],
            'table_id' => $bank_account_transfer->id,
            'table' => 'bank_account_transfers',
            'amount' => $input['amount'],
            'type' => 1,
        ]);

        // DB::commit();

        \Session::flash('alert-success', 'โยกเงินเรียบร้อย');

        return redirect()->back();

    }

    public function transferDelete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_histories = BankAccountHistory::where('table','=','bank_account_transfers')->whereTableId($input['bank_account_transfer_id'])->get();

        foreach($bank_account_histories as $bank_account_history) {

            if($bank_account_history->type == 1) {

                //decrement
                $bank_account = BankAccount::find($bank_account_history->bank_account_id);

                $bank_account->decrement('amount', $bank_account_history->amount);

            } else if ($bank_account_history->type == 2) {

                //increment
                $bank_account = BankAccount::find($bank_account_history->bank_account_id);

                $bank_account->increment('amount', $bank_account_history->amount);

            }

            $bank_account_history->delete();

        }

        BankAccountTransfer::find($input['bank_account_transfer_id'])->delete();

        DB::commit();

        \Session::flash('alert-success', 'ลบรายการโยกเงินสำเร็จ');

        return \redirect()->back();

    }

    public function withdraw(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        $bank_account_withdraws = BankAccountWithdraw::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->paginate(30)->appends(request()->except('page'));

        return view('agent.finances.withdraw',\compact('brand','bank_accounts','bank_account_withdraws','dates'));

    }

    public function withdrawScbSms($bank_account, $bank_account_from, $amount) {

        $scb_id = $bank_account->username;

        $scb_pass = $bank_account->password;

        $mobile_otp = 0;

        $bank_account_id = $bank_account->id;

        $bank_account = $bank_account->account;

        $customer_bank_account = $bank_account_from->bank_account;

        $customer_bank_code = $bank_account_from->bank->code_scb;

        $customer_amount = $amount;
            
        include('scb.withdraw.php');

        $send = get_otp_withdraw($scb_id,$scb_pass,$customer_amount,$customer_bank_account,$customer_bank_code,$mobile_otp,$bank_account);

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

                $bank_account_otp = BankAccountOtp::whereRefer($send['refer'])->whereStatus(0)->first();

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

        }else{

            $result['status'] = false;
            $result['msg'] = $send['detail'];

        }

        return $result;
    }

    public function withdrawStore(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_to = BankAccount::find($input['bank_account_id']);

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['brand_id'] = Auth::user()->brand_id;
        
        $input['user_id'] = Auth::user()->id;

        $bank_account_to->decrement('amount', $input['amount']);

        if(isset($input['slip'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            if(env('APP_ENV') == 'local') {

                $input['slip_url'] = Storage::url($storage);

            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));

            }

            $input['slip'] = $storage;

        } else {

            $input['slip_url'] = 'https://via.placeholder.com/150';

            $input['slip'] = '';

        }

        $bank_account_withdraw = BankAccountWithdraw::create($input);

        BankAccountHistory::create([
            'brand_id' => $input['brand_id'],
            'bank_account_id' => $input['bank_account_id'],
            'user_id' => $input['user_id'],
            'table_id' => $bank_account_withdraw->id,
            'table' => 'bank_account_withdraws',
            'amount' => $input['amount'],
            'type' => 2,
        ]);

        DB::commit();

        \Session::flash('alert-success', 'เบิกเงินเรียบร้อย');

        return redirect()->back();

    }

    public function withdrawDelete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_history = BankAccountHistory::where('table','=','bank_account_withdraws')->whereTableId($input['bank_account_withdraw_id'])->first();

        $bank_account = BankAccount::find($bank_account_history->bank_account_id);

        $bank_account->increment('amount', $bank_account_history->amount);

        BankAccountWithdraw::find($input['bank_account_withdraw_id'])->delete();

        DB::commit();

        \Session::flash('alert-success', 'ลบรายการเบิกเงินสำเร็จ');

        return \redirect()->back();

    }

    public function receive(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        $bank_account_receives = BankAccountReceive::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->paginate(30)->appends(request()->except('page'));

        return view('agent.finances.receive',\compact('brand','bank_accounts','bank_account_receives','dates'));

    }

    public function receiveStore(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_to = BankAccount::find($input['bank_account_id']);

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['brand_id'] = Auth::user()->brand_id;
        
        $input['user_id'] = Auth::user()->id;

        $bank_account_to->increment('amount', $input['amount']);

        if(isset($input['slip'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            // return response()->json($storage);

            if(env('APP_ENV') == 'local') {

                $input['slip_url'] = Storage::url($storage);

            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));

            }

            $input['slip'] = $storage;

        } else {

            $input['slip_url'] = 'https://via.placeholder.com/150';

            $input['slip'] = '';

        }

        $bank_account_withdraw = BankAccountReceive::create($input);

        BankAccountHistory::create([
            'brand_id' => $input['brand_id'],
            'bank_account_id' => $input['bank_account_id'],
            'user_id' => $input['user_id'],
            'table_id' => $bank_account_withdraw->id,
            'table' => 'bank_account_receives',
            'amount' => $input['amount'],
            'type' => 1,
        ]);

        DB::commit();

        \Session::flash('alert-success', 'เพิ่มรายรับสำเร็จ');

        return redirect()->back();

    }

    public function receiveDelete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_history = BankAccountHistory::where('table','=','bank_account_receives')->whereTableId($input['bank_account_receive_id'])->first();

        $bank_account = BankAccount::find($bank_account_history->bank_account_id);

        $bank_account->decrement('amount', $bank_account_history->amount);

        BankAccountReceive::find($input['bank_account_receive_id'])->delete();

        DB::commit();

        \Session::flash('alert-success', 'ลบรายการเบิกเงินสำเร็จ');

        return \redirect()->back();

    }

    public function return(Request $request) {

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $banks = Bank::all();

        $brand = Brand::find(Auth::user()->brand_id);

        $bank_accounts = BankAccount::whereBrandId($brand->id)->get();

        $bank_account_returns = BankAccountReturn::whereBrandId($brand->id)->whereBetween('updated_at', [$dates['start_date'],$dates['end_date']])->paginate(30)->appends(request()->except('page'));

        return view('agent.finances.return',\compact('brand','bank_accounts','bank_account_returns','dates','banks'));

    }

    public function returnStore(Request $request) {

        $input = $request->all();

        $bank_account_to = BankAccount::find($input['bank_account_id']);

        if($bank_account_to->active == 1) {

            return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ ลองใหม่อีกครั้งค่ะ '. $transfer['msg']]);

        }

        $brand = Brand::find($bank_account_to->brand_id);

        $input['amount'] = str_replace(',','',$input['amount']);

        $input['brand_id'] = Auth::user()->brand_id;
        
        $input['user_id'] = Auth::user()->id;

        $bank = Bank::find($input['bank_id']);

        if($bank_account_to->bank_id == 1) {

            if($bank_account_to->type == 0 || $bank_account_to->type == 1 || $bank_account_to->type == 3) {
            
                // $bank_account_to->update([
                //     'active' => 1,
                // ]);

                $api = new BotSCB($bank_account_to);
    
                $app_id = Helper::decryptString($bank_account_to->app_id, 1, 'base64');
        
                $token = Helper::decryptString($bank_account_to->token, 1, 'base64');
    
                $api->setLogin($app_id, $token);
                
                $api->login();
            
                $bank_account_to->update([
                    'active' => 0,
                ]);
    
                $api->setAccountNumber($bank_account_to->account);
    
                $transfer = $api->Transfer($input['bank_account'],$bank->code_scb,$input['amount']); // เลขบัญชี รหัสธนาคาร จำนวนเงิน
    
                if($transfer['status'] === false) {
    
                    return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);
    
                }
    
            } else if($bank_account_to->type == 6) {
    
                //SCBWithdrawSMS;
                $result_withdraw = $this->botWithdrawSCBEasy($brand,$bank_account_to,$bank_account_from->bank_id,$bank_account_from->account,$input['amount']);
                
                if($result_withdraw['status'] === false) {
                    
                    return redirect()->back()->withErrors([$result_withdraw['msg']]);
    
                }
    
            } else if ($bank_account_to->type == 9 || $bank_account_to->type == 10 || $bank_account_to->type == 11) {
            
                // $bank_account_to->update([
                //     'active' => 1,
                // ]);
    
                $api= new BotSCBPin($bank_account_to);
    
                $pin = $bank_account_to->pin; #pin เข้า app ดึงจาก db
        
                $deviceid = Helper::decryptString($bank_account_to->app_id, 1, 'base64');
    
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
    
                $api->setBaseParam($apiauth, $bank_account_to->account); 
    
                $transfer = $api->Transfer($input['bank_account'],$bank->code_scb,$input['amount']); // เลขบัญชี รหัสธนาคาร จำนวนเงิน
    
                if($transfer['status'] === false) {
    
                    return redirect()->back()->withErrors(['ไม่สามารถโอนเงินออกได้ในขณะนี้ '. $transfer['msg']]);
    
                }
            
                $bank_account_to->update([
                    'active' => 0,
                ]);
                
            }

        }

        $bank_account_to->decrement('amount', $input['amount']);

        if(isset($input['slip'])) {
            
            //put new image 
            $storage  = Storage::disk('public')->put('slips', $request->file('slip'));

            // return response()->json($storage);

            if(env('APP_ENV') == 'local') {

                $input['slip_url'] = Storage::url($storage);

            } else {

                $input['slip_url'] = secure_url(Storage::url($storage));

            }

            $input['slip'] = $storage;

        } else {

            $input['slip_url'] = 'https://via.placeholder.com/150';

            $input['slip'] = '';

        }

        $bank_account_withdraw = BankAccountReturn::create($input);

        BankAccountHistory::create([
            'brand_id' => $input['brand_id'],
            'bank_account_id' => $input['bank_account_id'],
            'user_id' => $input['user_id'],
            'table_id' => $bank_account_withdraw->id,
            'table' => 'bank_account_returns',
            'amount' => $input['amount'],
            'type' => 1,
        ]);

        // DB::commit();

        \Session::flash('alert-success', 'เพิ่มรายรับสำเร็จ');

        return redirect()->back();

    }

    public function botWithdrawSCBEasy($brand,$bank_account,$bank_id,$bank_account_to,$amount) {

        $bank_to = Bank::find($bank_id);

        $scb_id = $bank_account->username;

        $scb_pass = $bank_account->password;

        $mobile_otp = 0;

        $bank_account_id = $bank_account->id;

        $bank_account = $bank_account->account;

        $customer_bank_account = $bank_account_to;

        $customer_bank_code = $bank_to->code_scb;

        $customer_amount = $amount;
            
        include('scb.withdraw.php');

        BotEvent::create([
            'brand_id' => $brand->id,
            'event' => 'send value '
        ]);

        $send = get_otp_withdraw($scb_id,$scb_pass,$customer_amount,$customer_bank_account,$customer_bank_code,$mobile_otp,$bank_account);

        $result = [
            'status' => false,
            'msg' => null
        ];
        
        if($send['status'] == 1){

            $bank_account_refer = BankAccountRefer::create([
                'bank_account_id' => $bank_account_id,
                'refer' => $send['refer'],
            ]);

            BotEvent::create([
                'brand_id' => $brand->id,
                'event' => 'set refer '.$send['refer']
            ]);

            $ii=0;

            $checktime=date("YmdHis");

            $loop=5;

            while($ii<$loop){
            
                $ii++;
            
                sleep(10);

                $bank_account_otp = BankAccountOtp::whereRefer($bank_account_refer->refer)->whereStatus(0)->first();

                BotEvent::create([
                    'brand_id' => $brand->id,
                    'event' => 'wait for set otp '.date('Y-m-d H:i:s') 
                ]);

                if($bank_account_otp['otp'] != null) {
                    $confirm_data=confirm_otp($bank_account_otp['otp'],$send['__VIEWSTATE'],$send['__VIEWSTATEGENERATOR'],$customer_bank_code,$bank_account);

                    BotEvent::create([
                        'brand_id' => $brand->id,
                        'event' => 'confirm data for set otp '.\json_encode($confirm_data)
                    ]);

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

    public function returnDelete(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank_account_history = BankAccountHistory::where('table','=','bank_account_returns')->whereTableId($input['bank_account_return_id'])->first();

        $bank_account = BankAccount::find($bank_account_history->bank_account_id);

        $bank_account->decrement('amount', $bank_account_history->amount);

        BankAccountReturn::find($input['bank_account_return_id'])->delete();

        DB::commit();

        \Session::flash('alert-success', 'ลบรายการเบิกเงินสำเร็จ');

        return \redirect()->back();

    }
}
