<?php 

namespace App\Helpers;

class BetFlixApi {

    protected $api;

    protected $app_id;

    protected $ur = 'https://www.betflix.com';

    public function status() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{api_url}}/v4/status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-betflix: {{x-api-betflix}}',
                'x-api-key: {{x-api-key}}'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response, true); 

    }

}