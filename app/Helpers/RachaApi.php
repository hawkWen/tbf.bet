<?php

namespace App\Helpers;

class RachaApi
{
    public $agent;

    public $app_id;

    protected $url = 'https://agent.rachacasino.com/api';

    public function signature() {
    
        return md5($this->agent.$this->app_id);

    }

    public function setUrl() {

        return $this->url;

    }

    public function register($data) {

        // $data = [

        // ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->setUrl().'/member?signature='.$this->signature(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        // dd(json_decode($response, true));

        curl_close($curl);

        return json_decode($response,true);

    }
    
    public function status($data) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->setUrl().'/member?signature='.$this->signature(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response,true);

    }

    public function credit($data) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->setUrl().'/credit?signature='.$this->signature(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response,true);

    }

    public function changePassword($data) {

        // $data = json_encode([
        //     "username" => "",
        //     "password_old" => "",
        //     "password" => "",
        // ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->setUrl().'/member/change-password?signature='.$this->signature(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response,true);

    }

    public function creditAll() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->setUrl().'/credit-all?signature='.$this->signature(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response,true);

    }

    public function transfer($data) {

        // $data = json_encode([
        //     "username" => "test",
        //     "amount" => "123123",
        //     "type" => 1
        // ]);

        //2 deposit

        //3 withdraw

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->setUrl().'/transfer?signature='.$this->signature(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response,true);

    }
}
