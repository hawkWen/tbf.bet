<?php

namespace App\Helpers;

class KrungsriApi
{

    public $username;

    public $password;

    public $account;

    public $action;

    public function run() {

        $data = json_encode([
            'username' => $this->username,
            'password' => $this->password,
            'account' => $this->account,
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://45.77.40.179:9000/bot/krungsri/".$this->action,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response,true);

    }

}