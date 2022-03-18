<?php

namespace App\Http\Controllers\Bot;

use Carbon\Carbon;
use App\Models\BotEvent;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\BankAccountOtp;
use App\Helpers\BotSCBWithdraw;
use App\Models\BankAccountRefer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\BankAccountTransaction;

class BotWithdrawController extends Controller
{
    //
    public function index() {

        return view('bot');

    }

    public function withdraw(Request $request) {

        $input = $request->all();

        $bank_account = BankAccount::find($input['bank_account_id']);

        $scb_id = $bank_account->username;

        $scb_pass = $bank_account->password;

        $mobile_otp = 0;

        $bank_account = $bank_account->account;
        
        if($_POST['accno']&&$_POST['bankno']&&$_POST['amount']){
            
            include('scb.withdraw.php');

            $send = get_otp_withdraw($scb_id,$scb_pass,$_POST['amount'],$_POST['accno'],$_POST['bankno'],$mobile_otp,$bank_account);
            
            if($send['status']==1){
                
                $bank_account_refer = BankAccountRefer::create([
                    'bank_account_id' => $bank_account->id,
                    'refer' => $send['refer'],
                ]);

                $checktime=date("YmdHis");

                $ii=0;

                $loop=5;

                while($ii<$loop){
                
                    $ii++;
                
                    sleep(10);

                    $bank_account_otp = BankAccountOtp::whereRefer($bank_account_refer->refer)->whereStatus(0)->first();

                    if($bank_account_otp['otp'] != null) {
                        $confirm_data=confirm_otp($bank_account_otp['otp'],$send['__VIEWSTATE'],$send['__VIEWSTATEGENERATOR'],$_POST['bankno'],$bank_account);
                        if($confirm_data['status']==1){
                            $bank_account_otp->update([
                                'status' => 1,
                            ]);
                            \Session::flash('alert-success', 'โอนเรียบร้อย');
                            return redirect()->back();
                        }else{
                            $bank_account_otp->update([
                                'status' => 1,
                            ]);
                            \Session::flash('alert-danger', $send['detail']);
                            return redirect()->back();
                        }
                        $ii=$loop;
                        $otpin=true;
                    }
                    
                } 

                if(!$otpin){
                    \Session::flash('alert-danger','รอ otp นานเกินไป otp มาไม่ถึง');
                    return redirect()->back();
                }

            }else{

                \Session::flash('alert-danger', $send['detail']);
                return redirect()->back();

            }
        }

    }

    protected function cutstring($content,$text1,$text2){
        $fcontents2 = stristr($content, $text1); 
        $rest2 = substr($fcontents2,strlen($text1)); 
        $extra2 = stristr($fcontents2, $text2); 
        $titlelen2 = strlen($rest2) - strlen($extra2); 
        $gettitle2 = trim(substr($rest2, 0, $titlelen2)); 
        return $gettitle2;
    }

    public function getOtp() {

        $phone = strtolower($_GET['phone']);

        $text = $_GET['text'];
        
        BotEvent::create([
            'brand_id' => 0,
            'event' => $phone.' = '.$text
        ]);

        if($phone == "027777777"){
            
            $otp= $this->cutstring($text,"<OTP ",">");
            
            $refer= $this->cutstring($text,"<Ref. ",">");
            
            if($otp){	
                BankAccountOtp::create([
                    'otp' => $otp,
                    'refer' => $refer,
                    'status' => 0,
                ]);
            } else {
                
            }

        } 

        if($phone == 'truemoney') {

            BotEvent::create([
                'brand_id' => 0,
                'event' => 'truemoney text : '.$_GET['text']
            ]);

            $otp = $this->cutstring($text,'คือ ',' (R');

            $refer = $this->cutstring($text,'(Ref:',')');

            BankAccountOtp::create([
                'otp' => $otp,
                'refer' => $refer,
                'status' => 0,
            ]);

            BotEvent::create([
                'brand_id' => 0,
                'event' => $_GET['text'].' : '.$otp.' : '.$refer
            ]);

        }

        if($phone == "kbank") {

            // $date = Carbon::createFromFormat('d/m/y H:i',substr($sms,0,14));

            $account_to = substr($text,20,6);

            $account_from = $this->cutstring($text,'from A/C X','X Out');

            $amount = $this->cutstring($text,'Received ',' Baht');

            $balance = $this->cutstring($text,'Balance ',' Baht');

            $bank_accounts = BankAccount::select(DB::raw('*,SUBSTRING(account,4,6) As bank_account'))->get();

            // $bank_account = $bank_accounts->where('bank_account','=',$account_to);

            // $bank_account->update([
            //     'amount' => $balance,
            // ]);

            // $check_transaction = BankAccountTransaction::where('bank_account_id', $bank_account->id)->where('bank_account', $account)
            //     ->where('unix_time', $unix_time)
            //     ->first();

            // if(empty($check_transaction)) {

            //     BankAccountTransaction::create([
            //         'bank_account_id' => $bank_account->id,
            //         'code_bank' => '',
            //         'bank_account' => $account_from,
            //         'amount' => doubleval(str_replace(',', '', $amount)),
            //         'status' => 0,
            //         'transfer_at' => $date,
            //         'bank_id' => 4,
            //         'brand_id' => $bank_account->brand_id,
            //         'unix_time' => $date->timestamp,
            //         'status_transaction' => 0
            //     ]);

            // }

        }

    }
}
