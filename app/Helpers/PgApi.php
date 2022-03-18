<?php

namespace App\Helpers;

class PgApi
{

    public $agent;

    public $app_id;

    protected $end_point_url = 'https://api-prod.pgslot-api.com/partner/';

    public function hashing($data)
    {
        $password = json_encode($data);
        
        $iterations = 1000;

        $secret = $this->app_id;

        $hash = hash_pbkdf2("sha512", $password, $secret, $iterations, 64, true);

        return base64_encode($hash);
    }

    public function create($data)
    {

        $data = [
            'username' => $data['username'],
            'password' => $data['password'],
            'agent' => $this->agent
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        return json_decode($response, true);
    }

    public function credit($data) {

        $data = [
            'username' => $data['username'],
            'agent' => $this->agent
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/balance',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // dd($response);

        return json_decode($response, true);

    }

    public function changePassword($data) {

        $data = [
            'username' => $data['username'],
            'newPassword' => $data['newPassword'],
            'agent' => $this->agent
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/password',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }

    public function deposit($data) {

        $data = [
            'username' => $data['username'],
            'amount' => $data['amount'],
            'agent' => $this->agent
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/deposit',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }

    public function withdraw($data) {

        $data = [
            'username' => $data['username'],
            'amount' => $data['amount'],
            'agent' => $this->agent
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/withdraw',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }

    public function lanuch($data) {

        $data = [
            'username' => $data['username'],
            'agent' => $this->agent
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/launch',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }

    public function demo($data) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->end_point_url.'/games/demo',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }

    public function reportByDate($data) {
        
        $data = [
            "agent" => "pgball77",
            "startDate" => "2021-10-21",
            "endDate" => "2021-10-22",
            "userList" => ["pg239879"],
            "page" => 4,
            "limit" => 100
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->end_point_url.'/report/date',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => \json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-amb-signature: ' . $this->hashing($data),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);

    }
}
