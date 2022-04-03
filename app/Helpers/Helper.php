<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use App\User;
use App\Models\Agent;
use App\Models\Config;
use App\Models\Deposit;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Register;
use App\Models\Withdraw;
use App\Models\UserNotify;
use App\Models\WebSetting;
use Illuminate\Support\Carbon;
use App\Models\CustomerDeposit;
use App\Models\CustomerWithdraw;
use App\Models\ProductPriceValue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use App\Models\PromotionCost;

class Helper
{

    public static function notification($brand_id) {

        $customer_black_lists = Customer::whereBrandId($brand_id)->whereType(1)->get();

        $customer_deposits = CustomerDeposit::whereBrandId($brand_id)->select('id','promotion_id','customer_id','amount','bonus','created_at')
            ->whereIn('customer_id', $customer_black_lists->pluck('id'))
            ->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])
            ->orderBy('created_at','desc')->take(5)->get();

        $customer_promotion_costs = PromotionCost::whereBrandId($brand_id)->select('id','promotion_id','customer_id','amount','bonus','created_at')
            ->whereIn('customer_id', $customer_black_lists->pluck('id'))
            ->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')])
            ->orderBy('created_at','desc')->take(5)->get();

        $notifications = $customer_deposits->merge($customer_promotion_costs);
        
        $result_notifications = collect([]);

        foreach($notifications as $notification) {

            if($notification->getTable() == 'customer_deposits') {

                $message = 'ลูกค้า ('.$notification->customer->username.') ที่ถูกแบล็คลิสต์ได้เติมเงินเข้ามาเป็นจำนวนเงิน '. number_format($notification->amount,2).' บาท เมื่อเวลา '.$notification->created_at->format('d/m/Y H:i');

                $type = 1;

            } else if($notification->getTable() == 'promotion_costs') {

                $message = 'ลูกค้า ('.$notification->customer->username.') ถูกแบล็คลิสต์ได้ รับโปรโมชั่น '.$notification->promotion->name.' เป็นจำนวนเงิน '. number_format($notification->bonus,2).' บาท เมื่อเวลา '.$notification->created_at->format('d/m/Y H:i');

                $type = 2;

            }

            $data = [
                'id' => $notification->id,
                'message' => $message,
                'created_at' => $notification->created_at->format('d/m/Y H:i'),
                'type' => $type,
            ];

            $result_notifications->push($data);

        }

        // dd($result_notifications);

        return $result_notifications;

    }
    
    public static function encryptString($plaintext, $password, $encoding = null) {
        $user = User::find($password);
        $iv = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt($plaintext, "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext.$iv, hash('sha256', $password, true), true);
        return $encoding == "hex" ? bin2hex($iv.$hmac.$ciphertext) : ($encoding == "base64" ? base64_encode($iv.$hmac.$ciphertext) : $iv.$hmac.$ciphertext);
    }

    public static function decryptString($ciphertext, $password, $encoding = null) {
        $user = User::find($password);
        $ciphertext = $encoding == "hex" ? hex2bin($ciphertext) : ($encoding == "base64" ? base64_decode($ciphertext) : $ciphertext);
        if (!hash_equals(hash_hmac('sha256', substr($ciphertext, 48).substr($ciphertext, 0, 16), hash('sha256', $password, true), true), substr($ciphertext, 16, 32))) return null;
        return openssl_decrypt(substr($ciphertext, 48), "AES-256-CBC", hash('sha256', $password, true), OPENSSL_RAW_DATA, substr($ciphertext, 0, 16));
    }

    public static function detectUserOS() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
        $os_platform  = "Unknown OS Platform";
    
        $os_array = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;
    
        return $os_platform;

    }

    public static function getIPAddress() {  
        // 
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }  

    public static function detectUserBrowser() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser = "Unknown Browser";

        $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        );  

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;

        return $browser;

    }
    
    public static function getIPLocation() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } else if (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        $PublicIP = $ipaddress;

        $json = '';
        
        try {
            $json = file_get_contents("http://ipinfo.io/$PublicIP/geo");
        } catch (\Throwable $th) {
            // throw $th;
            // $json = [];
        }
        
        $json     = json_decode($json, true);
        if(isset($json['country'])) {
            $country  = $json['country'];
            $region   = $json['region'];
            $city     = $json['city'];
        }

        if(isset($country)) {
            return 'Country: '.$country.',City: '.$city.',IP: '.$PublicIP;
        } else {
            return $ipaddress;
        }
    }

    public static function dateThai($strDate,$showTime)
    {

        $strYear = date("y",strtotime($strDate));

        $strMonth= date("n",strtotime($strDate));

        $strDay= date("j",strtotime($strDate));

        $strHour= date("H",strtotime($strDate));

        $strMinute= date("i",strtotime($strDate));

        $strSeconds= date("s",strtotime($strDate));

        $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");

        $strMonthFullCut = Array("","มกราคม.","กุมภาพันธ์","มีนาคม","เมษายา","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

        $strMonthThai = $strMonthCut[$strMonth];
        $strMonthFullThai = $strMonthFullCut[$strMonth];

        if($showTime) {

            return "$strDay $strMonthThai $strYear $strHour:$strMinute:$strSeconds";

        } else {

            return "$strDay $strMonthThai $strYear";

        }

    }

    public static function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public static function slug($string){
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one
    }

    public static function getTimeMonitor() {

        $hour = (int)date('H');

        if($hour < 11) {

            $start_date = Carbon::yesterday()->format('Y-m-d').' 11:00:00';

            $end_date = Carbon::now()->format('Y-m-d').' 10:59:59';

        } else {


            $start_date = Carbon::now()->format('Y-m-d').' 11:00:00';

            $end_date = Carbon::tomorrow()->format('Y-m-d').' 10:59:59';

        }

        return [$start_date,$end_date];

    }

    public static function getDateReport($start_date,$end_date) {

        $dates = Helper::getTimeMonitor();

        $start_date = (isset($start_date)) ? Helper::dateToDB($start_date).' 11:00:00' : $dates[0];

        $end_date = (isset($end_date)) ? Helper::dateToDB($end_date).' 10:59:59' : $dates[1];

        $input_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $start_date)->format('d/m/Y');

        $input_end_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date)->format('d/m/Y');

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'input_start_date' => $input_start_date,
            'input_end_date' => $input_end_date,
        ];

    }

    public static function getDateMarketingReport($start_date,$end_date) {

        if($start_date == null) {

            $start_date = date('Y-m-d').' 00:00:00';

            $end_date = date('Y-m-d').' 23:59:59';

        } else {

            $start_date = Helper::dateToDB($start_date).' 00:00:00';

            $end_date =  Helper::dateToDB($end_date).' 23:59:59';

        }

        $input_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $start_date)->format('d/m/Y');

        $input_end_date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date)->format('d/m/Y');

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'input_start_date' => $input_start_date,
            'input_end_date' => $input_end_date,
        ];

    }

    public static function bonusCalculator($amount,$promotion) {

        $bonus = 0;

        if($promotion->type_promotion_cost != 3) {

            if($promotion) {

                if($promotion->type_cost == 1) {

                    $bonus = ($amount * $promotion->cost) / 100;

                } else if($promotion->type_cost == 2) {

                    $bonus = $promotion->cost;

                }

            }

            if($promotion->max < $bonus) {
                $bonus = $promotion->max;
            }

        }

        return $bonus;

    }

    public static function dateToDB($date){

        $exlode = explode('/',$date);

        return $exlode[2].'-'.$exlode[1].'-'.$exlode[0];

    }

    public static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^0-9\-]/', '', $string); // Removes special chars.
    }

    public static function getUserNotifyNonActive() {
        return UserNotify::whereUserId(Auth::user()->id)->whereActive(0)->count();
    }

    public static function getUserNotify() {
        return UserNotify::whereUserId(Auth::user()->id)->orderBy('created_at','desc')->get();
    }

    public static function remainTime($date) {
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }

    public static function getAge($year,$month,$day) {

        $then = mktime(1,1,1,$month,$day,$year);

        return(floor((time()-$then)/31556926));

    }

    public static function getNexmoCredit() {

        $config = Config::first();

        $current_balance = 'https://rest.nexmo.com/account/get-balance/'.$config->nexmo_key.'/'.$config->nexmo_secret;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $current_balance);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $body = curl_exec($ch);

        $result = json_decode($body);

        return $result;

    }

    public static function getCategory() {
        return Category::all();
    }

    public static function upload($image,$type,$key) {

        $input['imagename'] = $key.time().'.'.$image->getClientOriginalExtension();

        $image->move('uploads/'.$type.'/',$input['imagename']);

        return $input['imagename'];

    }

    public static function deleteFile($path,$file_name) {

        unlink(public_path().'/uploads/'.$path.'/'.$file_name);

    }

    public static function getCustomerEvent($event_id) {

        $customer = Customer::where('id','=',$event_id)->with('game','brand','bankAccount')->withTrashed()->first();

        return $customer;

    }

    public static function getCustomerDepositEvent($event_id) {

        $customer = CustomerDeposit::with('customer')->withTrashed()->first();

        return $customer;

    }

    public static function getCustomerWithdrawEvent($event_id) {

        $customer = CustomerWithdraw::with('customer')->withTrashed()->first();

        return $customer;

    }


    public static function uploadBase($img,$type,$key){

        $image = new ImageManager();
        $name = 'product-'.$key.time().Helper::generateCode().'.png';
        $path = public_path().'/uploads/'.$type.'/'.$name;
        $image->make(file_get_contents($img))->resize(800,800)->save($path);

        return $name;

    }

    public static function generateCode() {
        return strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 6));
    }
    
    public static function getSetting() {
        return Setting::first();
    }

}
