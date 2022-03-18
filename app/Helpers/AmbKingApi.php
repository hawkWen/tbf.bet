<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class AmbKingApi {

    public $agent;

    public $hash;

    public $key;

    protected $web = 'ambking';

    protected $url_end_point = 'https://api.ambexapi.com/api/v1';

    public function create($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $this->agent.$data['username'],
            "password" => $data['password'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/createUser/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        // {
        //     "code": 0,
        //     "msg": "SUCCESS"
        // }

        // {
        //     "code": 100011,
        //     "msg": "Username is duplicate."
        // }

        // print_r($data);

        // echo $this->url_end_point.'/ext/createUser/'.$this->hash.'/'.$this->agent.'<br>';

        // echo $this->key.'<br>';

        $response = curl_exec($curl);

        // dd($response);
        
        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function changePassword($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $data['username'],
            "password" => $data['password'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/changePassword/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);

        // {
        //     "code": 0,
        //     "msg": "SUCCESS"
        // }

        // {
        //     "code": 100011,
        //     "msg": "Username is duplicate."
        // }
        
        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function credit($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $data['username'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/getProfileAndCredit/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);

        // {
        //     "code": 0,
        //     "data": {
        //         "balance": 0,
        //         "currency": "THB",
        //         "lastPaymentID": null,
        //         "outStandingAmt": {
        //             "card": 0,
        //             "casino": 0,
        //             "hdp": 0,
        //             "keno": 0,
        //             "lotto": 0,
        //             "mixParlay": 0,
        //             "mixStep": 0,
        //             "poker": 0,
        //             "slot": 0
        //         },
        //         "username": "ambme111111"
        //     },
        //     "msg": "SUCCESS"
        // }
        
        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function deposit($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $data['username'],
            "balance" => (float)$data['amount'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/deposit/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);
    
        // {
        //     "code": 0,
        //     "data": {
        //         "afterCredit": 110,
        //         "beforeCredit": 10,
        //         "refId": "61f4f4b3aae49f0013e8dfd9"
        //     },
        //     "msg": "SUCCESS"
        // }

        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function withdraw($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $data['username'],
            "balance" => (float)$data['amount'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/withdrawal/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);
    
        // {
        //     "code": 0,
        //     "data": {
        //         "afterCredit": 109,
        //         "beforeCredit": 110,
        //         "refId": "61f4f51d8367e60015217cc2"
        //     },
        //     "msg": "SUCCESS"
        // }

        curl_close($curl);
        
        return json_decode($response,true);

    }
    
    public function redirectLogin($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $data['username'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/redirectLogin/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);
    
        // {
        //     "code": 0,
        //     "data": {
        //         "afterCredit": 109,
        //         "beforeCredit": 110,
        //         "refId": "61f4f51d8367e60015217cc2"
        //     },
        //     "msg": "SUCCESS"
        // }

        curl_close($curl);
        
        return json_decode($response,true);

    }

    public function gameList($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "tab" => $data['game'],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/gameList/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);
    
        // {
        //     "code": 0,
        //     "data": {
        //         "afterCredit": 109,
        //         "beforeCredit": 110,
        //         "refId": "61f4f51d8367e60015217cc2"
        //     },
        //     "msg": "SUCCESS"
        // }

        curl_close($curl);
        
        return json_decode($response,true);

    }
    
    public function startGame($data) {

        $data = [
            "agentUsername" => $this->agent,
            "key" => $this->key,
            "username" => $data['username'],
            "gameID" => $data['gameId'],
            "provider" => $data['provider'],
            "redirectUrl" => "https://tbf.bet/". $data['brand']."/member",
            "language" => 'end',
            "tab" => $data["game_type"],
            "web" => $this->web,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url_end_point.'/ext/startGame/'.$this->hash.'/'.$this->agent,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept-Charset: application/json'
            ),
        ));

        $response = curl_exec($curl);
    
        // {
        //     "code": 0,
        //     "data": {
        //         "afterCredit": 109,
        //         "beforeCredit": 110,
        //         "refId": "61f4f51d8367e60015217cc2"
        //     },
        //     "msg": "SUCCESS"
        // }

        curl_close($curl);

        return json_decode($response,true);

    }
}   

?>