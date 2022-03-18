<?php 

namespace App\Helpers;

use App\Models\BotEvent;
use App\Helpers\RachaApi;
use App\Helpers\FastbetApi;
use App\Helpers\FastbetBotApi;

class BotApi {

    public $game_id;

    public $this_id;

    public $server_api;

    public $app_id;

    public $token;

    public $hash;

    public $contact;
    
    public $name;

    public $email;

    public $telephone;

    public $username;

    public $password_old;

    public $password;

    public $agent_username;

    public $agent_password;

    public $agent_prefix;

    public $amount;

    public $type_api;

    public function register() {

        $response = [
            'status' => false,
            'data' => []
        ];

        //Gclub 
        if($this->game_id == 1) {

            $gclub_api = json_decode(file_get_contents($this->server_api.'/server-api/gclub/?add_user&username='.$this->agent_username.'&set_username='.$this->username.'&password='.$this->agent_password.'&name='.$this->telephone.'&pass='.$this->password),true);

            // echo $this->server_api.'/server-api/gclub/?add_user&username='.$this->agent_username.'&set_username='.$this->username.'&password='.$this->agent_password.'&name='.$this->telephone.'&pass='.$this->password;

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $gclub_api['username']
                    ],
                ];

            }

        }

        //Racha
        if($this->game_id == 3) {
            
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->agent_username;

            $racha_bot_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['name'] = $this->name;

            $data['password'] = $this->password;

            $data['credit'] = 0;

            $data['telephone'] = $this->telephone;

            $racha_bot_api_register = $racha_bot_api->register($data);

            if($racha_bot_api_register['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $racha_bot_api_register['response']['username']
                    ]
                ];

            }

        }

        //Fastbet 
        if($this->game_id == 5) {

            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->agent_username;

            $fastbet_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['password'] = $this->password;

            $data['telephone'] = $this->telephone;

            $data['name'] = $this->name;

            $data['email'] = $this->email;

            $register = $fastbet_api->create($data);

            // dd($register);

            if($register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];
            }

        }

        return $response;

    }

    public function deposit () {

        $response = [
            'status' => false,
            'data' => []
        ];

        //Gclub 
        if($this->game_id == 1) {

            // echo $this->server_api.'/server-api/gclub?deposit&username='.$this->agent_username.'&password='.$this->agent_password.'&user='.$this->username.'&amount='.$this->amount;
            
            // BotEvent::create([
            //     'brand_id' => $this->brand_id,
            //     'event' => $this->server_api.'/server-api/gclub?deposit&username='.$this->agent_username.'&password='.$this->agent_password.'&user='.$this->username.'&amount='.$this->amount,
            // ]);

            $gclub_api = json_decode(file_get_contents($this->server_api.'/server-api/gclub?deposit&username='.$this->agent_username.'&password='.$this->agent_password.'&user='.$this->username.'&amount='.$this->amount),true);

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                    'data' => [
                        'online' => $gclub_api['online'],
                    ]
                ];

            } else {

                $response = [
                    'status' => false,
                    'data' => [
                        'online' => $gclub_api['online'],
                    ]
                ];

            }

        }

        //Racha
        if($this->game_id == 3) {

            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->agent_username;

            $racha_bot_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['amount'] = $this->amount;

            $data['type'] = 2;

            $racha_bot_api_register = $racha_bot_api->transfer($data);

            if($racha_bot_api_register['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $racha_bot_api_register['username']
                    ]
                ];

            }

        }

        //Fastbet 
        if($this->game_id == 5) {
            
            //Api
            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->agent_username;
            
            $fastbet_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['amount'] = $this->amount;

            $deposit = $fastbet_api->deposit($data);

            if($deposit != null && $deposit['code'] == 0) {
                $response['status'] = true;
            }

            return $response;

        }

        return $response;

    }

    public function checkCredit() {

        $response = [
            'status' => false,
            'data' => []
        ];

        //Gclub 
        if($this->game_id == 1) {
        
            $gclub_api = json_decode(file_get_contents($this->server_api.'/server-api/gclub/?credit&username='.$this->agent_username.'&password='.$this->agent_password.'&user='.$this->username),true);

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                    'data' => [
                        'credit' => $gclub_api['Balance']
                    ]
                ];

            }

        }

        //Racha
        if($this->game_id == 3) {
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->agent_username;

            $racha_bot_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $racha_bot_api_credit = $racha_bot_api->checkCredit($data);

            if($racha_bot_api_credit['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $racha_bot_api_credit['username'],
                        'credit' => $racha_bot_api_credit['credit'],
                    ]
                ];

            }

        }

        //Fastbet 
        if($this->game_id == 5) {

            //Api
            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->agent_username;
            
            $fastbet_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $credit = $fastbet_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];
            }

        }

        return $response;

    }

    public function withdraw() {

        $response = [
            'status' => false,
            'data' => []
        ];

        //Gclub 
        if($this->game_id == 1) {
        
            $gclub_api = json_decode(file_get_contents($this->server_api.'/server-api/gclub/?withdraw&username='.$this->agent_username.'&password='.$this->agent_password.'&user='.$this->username.'&amount='.$this->amount),true);

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                ];

            }

        }

        //Racha
        if($this->game_id == 3) {

            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->agent_username;

            $racha_bot_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['amount'] = $this->amount;

            $data['type'] = 3;

            $racha_bot_api_deposit = $racha_bot_api->transfer($data);

            if($racha_bot_api_deposit['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $racha_bot_api_deposit['username']
                    ]
                ];

            }

        }

        //Fastbet 
        if($this->game_id == 5) {

            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->agent_username;
            
            $fastbet_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['amount'] = $this->amount;

            $deposit = $fastbet_api->withdraw($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
            }

        }

        return $response;

    }

    public function checKBet() {

        $response['status'] = false;

        //Racha
        if($this->game_id == 3) {

            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->agent_username;

            $racha_bot_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['amount'] = $this->amount;

            $data['type'] = 2;

            $racha_bot_api_register = $racha_bot_api->transfer($data);

            if($racha_bot_api_register['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $racha_bot_api_register['username'],
                    ]
                ];

            }

        }

        // Fastbet
        if($this->game_id == 5) {

            if($this->type_api == 1) {

                //Puppeteer
                $fastbet_bot_api = new FastbetBotApi();
    
                $fastbet_bot_api->ip = $this->server_api;
    
                $fastbet_bot_api->username = $this->agent_username;
    
                $fastbet_bot_api_bet = $fastbet_bot_api->creditRemain();

                if($fastbet_bot_api_bet['code'] === 200) {

                    $response = [
                        'status' => true,
                        'data' => [
                            'bet' => $fastbet_bot_api_bet['bet'],
                        ],
                    ];

                } else {

                    $response = [
                        'status' => false,
                        'data' => [
                            'bet' => $fastbet_bot_api_bet['bet'],
                        ],
                    ];

                }

            } else if ($this->type_api == 2) {
                //Api
                $response = [
                    'status' => false,
                ];

            }

        }

        return $response;

    }

    public function resetPassword() {

        //Fastbet
        if($this->game_id == 5) {
            //Api
            $response['status'] = false;

            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->agent_username;
            
            $fastbet_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['password'] = $password;

            $credit = $fastbet_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;

        }

        //Racha
        if($this->game_id == 3) {
            
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->agent_username;

            $racha_bot_api->app_id = $this->app_id;

            $data['username'] = $this->username;

            $data['password_old'] = $this->password_old;

            $data['password'] = $this->password;

            $racha_bot_api_bet = $racha_bot_api->changePassword($data);

            if($racha_bot_api_bet['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'bet' => $racha_bot_api_bet['creditRemain']
                    ]
                ];

            }

        }

        return $response;

    }

}
