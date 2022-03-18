<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class FastbetApi {

    public $agent;

    public $app_id;

    public $url_end_point = 'https://topup-fastbet.askmebet.io/v0.1/partner/member';

    public function hashing($string) {

        return md5($string.':'.$this->agent);

    }

    public function create($data) {

        $data = [
            'memberLoginName' => $data['username'],
            'memberLoginPass' => $data['password'],
            'phoneNo' => $data['telephone'],
            'contact' => $data['name'],
            'signature' => $this->hashing($data['username'].':'.$data['password'])
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://topup-fastbet.askmebet.io/v0.1/partner/member/create/".$this->app_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        // dd($response);
        
        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function createAndDeposit($data,$amount,$agent_name) {

        $response = [
            'status_create' => false,
        ];

        $data_create = [
            'memberLoginName' => $data['username'],
            'memberLoginPass' => $data['password'],
            'phoneNo' => $data['telephone'],
            'contact' => $data['name'],
            'signature' => $this->hashing($data['username'].':'.$data['password'])
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://topup-fastbet.askmebet.io/v0.1/partner/member/create/".$this->app_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data_create),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response_create = curl_exec($curl);

        curl_close($curl); 

        $check_response_create = json_decode($response_create,true);

        // dd($data_create,$check_response_create);

        if($check_response_create['code'] == 0) {

            $response['status_create'] = true;

            $username = $check_response_create['result']['username'];

            $data_deposit = [
                'amount' => $amount,
                'signature' => $this->hashing($amount.':'.$username),
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://topup-fastbet.askmebet.io/v0.1/partner/member/deposit/".$this->app_id."/".$username,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($data_deposit),
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Accept-Charset: application/json"
                ),
            ));

            $response_deposit = curl_exec($curl);

            curl_close($curl);

        }

        

        return $response;

    }

    public function resetPassword($data) {

        $data_post = [
            'password' => $data['password'],
            'signature' => $this->hashing($data['password'])
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_end_point.'/reset-password/'.$this->app_id.'/'.$data['username'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($data_post),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function deposit($data) {

        $data_deposit = [
            'amount' => $data['amount'],
            'signature' => $this->hashing($data['amount'].':'.$data['username']),
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_end_point.'/deposit/'.$this->app_id.'/'.$data['username'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data_deposit),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function withdraw($data) {

        $data_withdraw = [
            'amount' => $data['amount'],
            'signature' => $this->hashing($data['amount'].':'.$data['username']),
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_end_point.'/withdraw/'.$this->app_id.'/'.$data['username'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data_withdraw),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function credit($data) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_end_point.'/credit/'.$this->app_id.'/'.$data['username'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function winLoss() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url_end_point.'/yesterdayTurnOver/findAll/'.$this->app_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response,true);


    }

    public function winLossNew($data) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://topup-fastbet.askmebet.io/v0.1/partner/member/winLose/'.$this->app_id.'/'.$data['username'].'/'.$data['refer_id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept-Charset: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response,true);

    }
    
}   

?>