<?php 

namespace App\Helpers;

class UfaApi {

    private $url = 'https://kraken.mrwed.cloud/partner';

    public $api_key;

    public $agent_username;

    public $agent_password;

    public function create($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/auth',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);


    }

    public function login($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/auth/login',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);

    }

    public function credit($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username']
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/user/credit',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);

    }

    public function setCreditLimit($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username'],
            "creditLimit" => $data['credit_limit'],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/user/credit-limit/set',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);

    }

    public function addCredit($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username'],
            "credit" => $data['credit'],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/user/credit/add',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);

    }

    public function removeCredit($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username'],
            "credit" => $data['credit'],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/user/credit/del',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);

    }

    public function changePassword($data) {

        $data = [
            'agentUsername' => $this->agent_username,
            'agentPassword' => $this->agent_password,
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url.'/user/change-password',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Origin: https://localhost',
                    'x-api-key: '.$this->api_key,
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return \json_decode($response, true);
    
    }

}