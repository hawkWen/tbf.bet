<?php

namespace App\Http\Controllers\Agent;

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

class HomeController extends Controller
{
    //
    public function index(Request $request) {

        $brand = Brand::find(Auth::user()->brand_id);

        $dates = Helper::getDateReport($request->get('start_date'),$request->get('end_date'));

        $customers = Customer::whereBrandId($brand->id)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $customer_news = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customers->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();

        $customer_deposits = CustomerDeposit::whereBrandId($brand->id)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $customer_withdraws = CustomerWithdraw::whereBrandId($brand->id)->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();

        $promotion_costs = PromotionCost::whereBrandId($brand->id)->whereIn('status',[0,1])->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->get();
        
        $group_by_promotion_costs = PromotionCost::with('promotion')->select('promotion_id',DB::raw('SUM(bonus) as bonus'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->whereBrandId($brand->id)->groupBy('promotion_id')->get();

        $bank_accounts = BankAccount::whereBrandId($brand->id)->orderBy('type')->get();

        $customer_all = Customer::whereBrandId($brand->id)->get();

        $customer_active = CustomerDeposit::select('customer_id')->whereIn('customer_id',$customer_all->pluck('id'))->whereBetween('created_at', [$dates['start_date'],$dates['end_date']])->groupBy('customer_id')->get();

        $annoucements = Annoucement::orderBy('created_at','desc')->take(2)->get();

        $customer_deposit_tops = CustomerDeposit::whereBrandId($brand->id)->select('customer_id',DB::raw('SUM(amount) as total_deposit'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_deposit','desc')->groupBy('customer_id')->take(5)->get();

        $customer_promotion_tops = PromotionCost::whereBrandId($brand->id)->select('customer_id',DB::raw('SUM(bonus) as total_bonus'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_bonus','desc')->groupBy('customer_id')->take(5)->get();

        $customer_withdraw_tops = CustomerWithdraw::whereBrandId($brand->id)->select('customer_id',DB::raw('SUM(amount) as total_withdraw'))->with('customer')->whereBetween('created_at', [$dates['start_date'], $dates['end_date']])->orderBy('total_withdraw','desc')->groupBy('customer_id')->take(5)->get();

        return view('agent.home',compact('brand','dates','customers','customer_deposits','customer_withdraws','promotion_costs','bank_accounts','customers','customer_news','group_by_promotion_costs','customer_all','customer_active','annoucements','customer_deposit_tops','customer_withdraw_tops','customer_promotion_tops'));

    }

    public function bankAccountUpdateStatus(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        BrandBankAccount::find($input['bank_account_id'])->update([
            'status_'.$input['type'] => $input['status'],
        ]);

        DB::commit();

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

    public function checkCredit(Request $request) {

        $input = $request->all();

        $customer = Customer::find($input['customer_id']);

        $brand = Brand::find($customer->brand_id);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $api_credit = $api->credit($data);

        if ($api_credit['status'] == true) {

            $credit['data']['credit'] = $api_credit['data']['credit'];

            $customer->update([
                'credit' => $credit['data']['credit'],
            ]);
            
        } else {

            $api_credit['data']['credit'] = $customer->credit;
        }

        return response()->json([
            'code' => 200,
            'data' => [
                'credit' => $customer->credit,
            ],
            'msg' => '',
        ]);

    }

    public function botScb() {

        return view('agent.bots.scb');

    }

    public function getFlag(Request $request) {

        header('Access-Control-Allow-Origin: *');
        error_reporting(0);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.uuidgenerator.net/?fbclid=IwAR24_g6lI9hxOCjgWAYqBKRJR05KeVDD8H1o7YpigUcekBTfk_aBFcAiTFg',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'authority: www.uuidgenerator.net',
                'pragma: no-cache',
                'cache-control: no-cache',
                'sec-ch-ua: "Chromium";v="94", "Google Chrome";v="94", ";Not A Brand";v="99"',
                'sec-ch-ua-mobile: ?0',
                'sec-ch-ua-platform: "Windows"',
                'upgrade-insecure-requests: 1',
                'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36',
                'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'sec-fetch-site: same-origin',
                'sec-fetch-mode: navigate',
                'sec-fetch-user: ?1',
                'sec-fetch-dest: document',
                'referer: https://www.uuidgenerator.net/?fbclid=IwAR24_g6lI9hxOCjgWAYqBKRJR05KeVDD8H1o7YpigUcekBTfk_aBFcAiTFg',
                'accept-language: en-US,en;q=0.9',
                'cookie: _ga=GA1.2.1102397423.1633506923; _gid=GA1.2.1893954129.1633506923; _uuidgenerator_net_session=pI%2B96uh8VOYeQ45i8cf%2FY5XfOciRnXUL455e%2F1xti9fHl1ShfBWXEiBYe04iAldMto7%2BiaI6%2BvV58%2BxwIxTgDvtHhxg3dPUEF2r5vD%2Bksg05Mmcp3w3Gqt7BWjHwLngnuakpYDgURVNrUbjG8Hph8ROzmShay5EbqCDfQiz%2BUaqHPD2i3JmE868lvAh7uKWHRxMxEcyu20MCrtPw1jY7bOiydb9NkQOFsUDP2eIwpNmUJQzD%2BNhN9xh4ibTFVq74acoklnhSZrBWyjcUG%2FYtLs1bouE5PE0VRLgngYHcuHyauQ%3D%3D--wsBrH1hD%2B8dqFdCJ--yn5YQX96mlRbTVsb3SO%2B%2BA%3D%3D; _gat_gtag_UA_7216971_4=1; _uuidgenerator_net_session=tRpeVsYdeeD5UWkZJr%2BzkQT9s66wZOrzt9nODE5A0pIrkkgJtj9mQ6uqRBHIboH72nVy2jjpCCpFUAFWgHRhAQ2d9cp0aUOHP4ZFPIbxQjhFMHa4j6y0wOhrOYIddHPOGithgqAT9I1aNc4Yl8Ax4L2e9vqOF9Oxg6RDtn2c4loFOT47jksviIuzi8unsDtE67MFu57ydblx3fuV8tAZ9TvQHZV318kbv48I4QBTLFAqKQM52TuanCHpURAw3ApBkcXI%2F277Tk1AolnzPEHJOj8%2BNJ2OArI3O3lYdMZBbNn7Eg%3D%3D--c34poeu2RnqeXxmG--EbBt%2FHHqFCONTIJ8zCZLlg%3D%3D'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $datapre = explode("generated-uuid", $response);
        $datapre1 = explode(">", $datapre[1]);
        $datapre2 = explode("<", $datapre1[1]);
        $deviceId=$datapre2[0];




        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fasteasy.scbeasy.com:8443/v1/login/getMigrationFlag',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"deviceId":"'.$deviceId.'","userMode":"INDIVIDUAL"}',
            CURLOPT_HTTPHEADER => array(
                'Accept-Language:       th',
                'scb-channel:   APP',
                'user-agent:         Android/10;FastEasy/3.36.0/4024',
                'Content-Type:   application/json; charset=UTF-8',
                'Host:   fasteasy.scbeasy.com:8443',
                'Connection:   close',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);



        $data = json_decode($response,true);
        $status_result=$data['status']['description'];

        if ($status_result=='สำเร็จ') {
            $data = array ('msg'=>$deviceId,'status'=>200);
            echo json_encode($data);
        }


    }

    public function register(Request $request) {

        $input = $request->all();

        error_reporting(0);

        $cardId=trim($input['cardId']);
        $dateOfBirth=trim($input['dateOfBirth']);
        $MobilePhoneNo=trim($input['MobilePhoneNo']);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v1/registration/verifyuser',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_HEADER=> 1,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"cardId":"'.$cardId.'","cardType":"P1","dateOfBirth":"'.$dateOfBirth.'"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Content-Type: application/json;charset=UTF-8',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response = curl_exec($curl);



        curl_close($curl);


        preg_match_all('/(?<=Api-Auth: ).+/', $response, $Auth);
        $Auth=$Auth[0][0];

        if ($Auth=="") {
            $data = array ('msg'=>'ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบ','status'=>500);
            echo json_encode($data);
            exit();
        }


        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v1/profiles/mobilelist',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"flag":"all","mobileNo":""}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$Auth,
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Content-Type: application/json;charset=UTF-8',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);



        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v1/profiles/generateOTP',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"AccountName":"","AccountNumber":"","Amount":"","DestinationBank":"","MobilePhoneNo":"'.$MobilePhoneNo.'","eventNotificationPolicyId":"FastEasyRegisteration_TH","policyId":"SCB_FastEasy_OTPPolicy","realActorId":"FastEasyApp","storeId":"SystemTokenStore"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$Auth,
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Content-Type: application/json; charset=UTF-8',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response,true);

        $status=$data['status']['statusdesc'];

        if ($status=='SUCCESS') {

            $bank_account_scb = BankAccountScb::whereBankAccount($input['bankAccount'])->first();

            if(!$bank_account_scb) {

                BankAccountScb::create([
                    'bank_account' => $input['bankAccount'],
                    'name' => $input['name'],
                    'telephone' => $input['MobilePhoneNo'],
                    'personal_id' => $input['cardId'],
                    'token' => $input['Auth'],
                    'remark' => $input['remark']
                ]);

            }

            $data = array ('msg'=>$data['tokenUUID'],'ref'=>$data['pac'],'Auth'=>$Auth,'status'=>200);
            echo json_encode($data);

        }else{
            $data = array ('msg'=>'ข้อมูลไม่ถูกต้อง กรุณาลองใหม่','status'=>500);
            echo json_encode($data);
            exit();
        }

    }

    public function cfOtp(Request $request) {

        $input = $request->all();
        error_reporting(0);
        $otp=trim($input['Otp']);
        $tokenUUID=trim($input['tokenUUID']);
        $Auth=trim($input['Auth']);
        $pin=trim($input['pin']);
        $deviceId=trim($input['deviceId']);
        $MobilePhoneNo=trim($input['MobilePhoneNo']);


        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v2/profiles/allowadddevice',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HEADER=> 1,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'otp:  '.$otp,
            'tokenUUID:  '.$tokenUUID,
            'Content-Type: application/json',
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth:  '.$Auth,
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response = curl_exec($curl);


        preg_match_all('/(?<=Api-Auth: ).+/', $response, $Auth1);
        $Auth1=$Auth1[0][0];



        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/isprint/soap/preAuth',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"loginModuleId":"MovingPseudo"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$Auth1,
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Content-Type: application/json; charset=UTF-8',
            'Content-Length: 32',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response1 = curl_exec($curl1);

        curl_close($curl1);


        $data = json_decode($response1,true);

        $json_result= [];

        $hashType1=$data['e2ee']['oaepHashAlgo'];
        $Sid1=$data['e2ee']['e2eeSid'];
        $ServerRandom1=$data['e2ee']['serverRandom'];
        $pubKey1=$data['e2ee']['pubKey'];


        $hashType2=$data['e2ee']['pseudoOaepHashAlgo'];
        $Sid2=$data['e2ee']['pseudoSid'];
        $ServerRandom2=$data['e2ee']['pseudoRandom'];
        $pubKey2=$data['e2ee']['pseudoPubKey'];




        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://service.fast-x.app/pin/encrypt",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "Sid=".$Sid1."&ServerRandom=".$ServerRandom1."&pubKey=".$pubKey1."&pin=".$pin."&hashType=".$hashType1,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded"
        ),
        ));

        $encrypt1 = curl_exec($curl);


        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://service.fast-x.app/pin/encrypt",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "Sid=".$Sid2."&ServerRandom=".$ServerRandom2."&pubKey=".$pubKey2."&pin=".$pin."&hashType=".$hashType2,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded"
        ),
        ));

        $encrypt2 = curl_exec($curl);


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v3/login',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HEADER=> 1,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"deviceId":"'.$deviceId.'","e2eeSid":"'.$Sid1.'","encryptPin":"'.$encrypt1.'","pseudoPin":"'.$encrypt2.'","pseudoSid":"'.$Sid2.'"}',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$Auth1,
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Content-Type: application/json; charset=UTF-8',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        preg_match_all('/(?<=Api-Auth: ).+/', $response, $Auth2);
        $Auth2=$Auth2[0][0];


        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fasteasy.scbeasy.com/v1/profiles/devices',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept-Language: th',
            'scb-channel: APP',
            'Api-Auth: '.$Auth2,
            'User-Agent: android/10;FastEasy/3.38.0/4219',
            'Content-Type: application/json; charset=UTF-8',
            'Host: fasteasy.scbeasy.com:8443',
            'Connection: Keep-Alive',
            'Accept-Encoding: gzip'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        $data_result = json_decode($response,true);

        $status_result=$data_result["status"]["description"];
        $deviceId=$data_result["data"][0]["deviceId"];



        if ($status_result=='สำเร็จ') {
        // ตรงนี้ บันทึก $deviceId, token ใน db ไม่ต้อง return ออกไป
        // $deviceId = $deviceId, token = $Auth2
            BankAccountScb::whereTelephone($input['MobilePhoneNo'])
                ->update([
                    'pin' => $pin,
                    'device_id' => $deviceId,
                ]);
            $data = ['msg' => 'สำเร็จ', 'status' => 200];
            echo json_encode($data);
        } else {
            $data = array ('msg'=>'PINCODE ไม่ถูกต้องกรุณาลองใหม่ไม่เกิน 3 ครั้ง','status'=>500);
            echo json_encode($data);
        }


    }
}
