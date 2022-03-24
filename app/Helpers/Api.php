<?php 

namespace App\Helpers;

use Carbon\Carbon;
use App\BrandAgent;
use App\Helpers\AmbApi;
use App\Helpers\MvpApi;
use App\Helpers\UfaApi;
use App\Helpers\Auto456;
use App\Models\BotEvent;
use App\Models\Customer;
use App\CustomerBetTotal;
use App\Helpers\RachaApi;
use App\Helpers\AmbFunApi;
use App\Helpers\FastbetApi;
use App\Models\CustomerBet;
use App\Models\CustomerRefer;
use App\Helpers\FastbetBotApi;
use App\Models\CustomerBetDetail;
use App\Helpers\AmbKingApi;
use App\Models\BotLog;

class Api {

    //register
    public $brand;

    public function __construct($brand) {

        $this->brand = $brand;

        // if($this->brand->agent_order == 2) {
        //     $this->brand->agent_username = $this->brand->agent_username_2;
        //     $this->brand->agent_password = $this->brand->agent_password_2;
        // }

    }

    public function register($data) {

        $response = [
            'status' => false,
            'data' => []
        ];

        if($this->brand->game_id == 1) {
            //Gclub
            $agent = $this->brand->agents->where('agent_order', $this->brand->agent_order)->first();

            // dd($agent,$data);

            $gclub_api = json_decode(file_get_contents($this->brand->server_api.'/server-api/gclub/?add_user&username='.$agent->agent_username.'&set_username='.$data['username'].'&password='.$agent->agent_password.'&name='.$data['name'].'&pass='.$data['password']),true);

            // dd($gclub_api);

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $gclub_api['username']
                    ],
                ];

            }
            

        }else if($this->brand->game_id == 2) {

            $ufa_api = new UfaApi();

            $ufa_api->api_key = $this->brand->app_id;

            $ufa_api->agent_username = $this->brand->agent_username;

            $ufa_api->agent_password = $this->brand->agent_password;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $ufa_api_register = $ufa_api->create($data);

            if($ufa_api_register['status'] === 'success') {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $ufa_api_register['ufa_username']
                    ]
                ];

            } else {

                $response = [
                    'status' => false,
                    'data' => [
                        'message' => $ufa_api_register['errorCode'].' '.$ufa_api_register['message']
                    ]
                ];

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $ufa_api_register['errorCode'].' : '.$ufa_api_register['message']
                ]);

            }

        } else if($this->brand->game_id == 3) {
            //RachaCasino
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->brand->agent_username;

            $racha_bot_api->app_id = $this->brand->app_id;

            $data['username'] = $this->brand->agent_prefix.$data['username'];

            $data['name'] = $data['name'];

            $data['password'] = $data['password'];

            $data['credit'] = 0;

            $data['telephone'] = $data['contact'];

            $racha_bot_api_register = $racha_bot_api->register($data);

            if($racha_bot_api_register['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'username' => $racha_bot_api_register['response']['username']
                    ]
                ];

            }

        } else if($this->brand->game_id == 5) {
            //Fastbet
            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->brand->agent_username;

            $fastbet_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $data['telephone'] = $data['contact'];

            $data['contact'] = $data['name'];

            $register = $fastbet_api->create($data);

            if($register['code'] == 0) {
                
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];

            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $register['message']
                ]);
            }

        } else if($this->brand->game_id == 6) {
            //Uking
            $uking_api = new UkingApi();

            $uking_api->agent = $this->brand->agent_username;

            $uking_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $data['telephone'] = $data['contact'];

            $data['contact'] = $data['name'];

            $register = $uking_api->create($data);

            if($register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $register['message']
                ]);
            } 

        } else if($this->brand->game_id == 7) {
            //Uking
            $amb_api = new AmbApi();

            $amb_api->agent = $this->brand->agent_username;

            $amb_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $data['telephone'] = $data['contact'];

            $data['contact'] = $data['name'];

            $register = $amb_api->create($data);

            if($register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $register['message']
                ]);
            }
        } else if($this->brand->game_id == 8) {
            //PG
            $pg_api = new PgApi();

            $pg_api->agent = $this->brand->agent_username;

            $pg_api->app_id = $this->brand->app_id;

            $data['username'] = $this->brand->agent_prefix.$data['username'];

            $data['password'] = $data['password'];

            $pg_api_register = $pg_api->create($data);

            if($pg_api_register['status']['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $pg_api_register['data']['username'];
            }else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $pg_api_register['status']['message'],
                ]);
            }

        } else if($this->brand->game_id == 9) {
            //MVP
            $mvp_api = new MvpApi();

            $mvp_api->agent = $this->brand->agent_username;

            $mvp_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $data['telephone'] = $data['contact'];

            $data['contact'] = $data['name'];

            $register = $mvp_api->create($data);

            if($register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];
            }else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $register['message']
                ]);
            }
        } else if($this->brand->game_id == 10) {
            //456bet
            $auto_api = new Auto456();

            $auto_api->agent = $this->brand->agent_username;

            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $data['telephone'] = $data['contact'];

            $data['contact'] = $data['name'];

            $register = $auto_api->create($data);

            if($register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $register['message']
                ]);
            }

        } else if($this->brand->game_id == 11) {
            
            $auto_api = new AmbFunApi();

            $auto_api->agent = $this->brand->agent_username;

            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $data['telephone'] = $data['contact'];

            $data['contact'] = $data['name'];

            $register = $auto_api->create($data);

            if($register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $register['result']['username'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $register['message']
                ]);
            }
        } else if ($this->brand->game_id == 12) {

            $amb_king_api = new AmbKingApi();

            $amb_king_api->agent = $this->brand->agent_username;

            $amb_king_api->hash = $this->brand->hash;

            $amb_king_api->key = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $amb_king_api_register = $amb_king_api->create($data);

            if($amb_king_api_register['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $this->brand->agent_username.$data['username'];
            }

        }

        return $response;

    }

    //deposit
    public function deposit($data) {

        $response = [
            'status' => false,
            'data' => []
        ];

        if($this->brand->game_id == 1) {
            //Gclub
            $agent = $this->brand->agents->where('agent_order', $data['agent_order'])->first();

            // echo $this->brand->server_api.'/server-api/gclub?deposit&username='.$agent->agent_username.'&password='.$agent->agent_password.'&user='.$data['username'].'&amount='.$data['amount'];
            // exit;

            $gclub_api = json_decode(file_get_contents($this->brand->server_api.'/server-api/gclub?deposit&username='.$agent->agent_username.'&password='.$agent->agent_password.'&user='.$data['username'].'&amount='.$data['amount']),true);            

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                    'data' => [
                        'online' => $gclub_api['online'],
                        'ref' => 0,
                    ]
                ];

            } else {

                $response = [
                    'status' => false,
                    'data' => [
                        'online' => $gclub_api['online'],
                        'ref' => 0,
                    ]
                ];

            }
        } else if($this->brand->game_id == 2) {

            $ufa_api = new UfaApi();

            $ufa_api->api_key = $this->brand->app_id;

            $ufa_api->agent_username = $this->brand->agent_username;

            $ufa_api->agent_password = $this->brand->agent_password;

            $data['username'] = $data['username'];

            $data['credit'] = $data['amount'];

            $ufa_api_deposit = $ufa_api->addCredit($data);

            if($ufa_api_deposit['status'] === 'success') {

                $response = [
                    'status' => true,
                    'data' => [
                        'ref' => 0
                    ]
                ];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $ufa_api_deposit['errorCode'].' : '.$ufa_api_deposit['message']
                ]);

            }

        } else if($this->brand->game_id == 3) {
            //RachaCasino
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->brand->agent_username;

            $racha_bot_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $data['type'] = 2;

            $racha_bot_api_register = $racha_bot_api->transfer($data);

            if($racha_bot_api_register['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'ref' => 0,
                    ]
                ];

            } 

        } else if($this->brand->game_id == 5) {
            //Fastbet
            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->brand->agent_username;
            
            $fastbet_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $fastbet_api->deposit($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $deposit['result']['ref'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }
            

        } else if($this->brand->game_id == 6) {
            //Uking
            $uking_api = new UkingApi();

            $uking_api->agent = $this->brand->agent_username;
            
            $uking_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $uking_api->deposit($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $deposit['result']['ref'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 7) {
            //AmbApi
            $amb_api = new AmbApi();

            $amb_api->agent = $this->brand->agent_username;
            
            $amb_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $amb_api->deposit($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $deposit['result']['ref'];
                $response['data']['credit'] = $deposit['result']['after'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 8) {
            //PG
            $pg_api = new PgApi();

            $pg_api->agent = $this->brand->agent_username;

            $pg_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $pg_api_deposit = $pg_api->deposit($data);

            if($pg_api_deposit['status']['code'] == 0) {
                $response['status'] = true;
                $response['data']['username'] = $data['username'];
                $response['data']['ref'] = 0;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $pg_api_deposit['status']['message']
                ]);
            }

        } else if($this->brand->game_id == 9) {
            //AmbApi
            $mvp_api = new MvpApi();

            $mvp_api->agent = $this->brand->agent_username;
            
            $mvp_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $mvp_api->deposit($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $deposit['result']['ref'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 10) {
            //AmbApi
            $auto_api = new Auto456();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $auto_api->deposit($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $deposit['result']['ref'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 11) {
            //AmbApi
            $auto_api = new AmbFunApi();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $auto_api->deposit($data);

            if($deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $deposit['result']['ref'];
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if ($this->brand->game_id == 12) {

            $amb_king_api = new AmbKingApi();

            $amb_king_api->agent = $this->brand->agent_username;

            $amb_king_api->hash = $this->brand->hash;

            $amb_king_api->key = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $amb_king_api_deposit = $amb_king_api->deposit($data);

            if($amb_king_api_deposit['code'] == 0) {
                $response['status'] = true;
                $response['data']['ref'] = $amb_king_api_deposit['data']['refId'];
            }

        }

        if($response['data']) {

            $customer_refer = CustomerRefer::whereCustomerId($data['customer_id'])->update([
                'status' => 1,
            ]);

            CustomerRefer::create([
                'brand_id' => $this->brand->id,
                'customer_id' => $data['customer_id'],
                'username' => $data['username'],
                'refer_id' => $response['data']['ref'],
                'status' => 0,
            ]);

        }

        return $response;

    }

    //withdraw
    public function withdraw($data) {

        $response = [
            'status' => false,
            'data' => []
        ];

        if($this->brand->game_id == 1) {
            //Gclub
            $agent = $this->brand->agents->where('agent_order', $data['agent_order'])->first();

            // if($data['agent_order'] == 2) {

            //     // dd(2);
            //     $gclub_api = json_decode(file_get_contents($this->brand->server_api.'/server-api/gclub/?withdraw&username='.$this->brand->agent_username_2.'&password='.$this->brand->agent_password_2.'&user='.$data['username'].'&amount='.$data['amount']),true);

            // } else {

                // dd(1);
                $gclub_api = json_decode(file_get_contents($this->brand->server_api.'/server-api/gclub/?withdraw&username='.$agent->agent_username.'&password='.$agent->agent_password.'&user='.$data['username'].'&amount='.$data['amount']),true);

            // }

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                ];

            }

        } else if($this->brand->game_id == 2) {

            $ufa_api = new UfaApi();

            $ufa_api->api_key = $this->brand->app_id;

            $ufa_api->agent_username = $this->brand->agent_username;

            $ufa_api->agent_password = $this->brand->agent_password;

            $data['username'] = $data['username'];

            $data['credit'] = $data['amount'];

            $ufa_api_withdraw = $ufa_api->removeCredit($data);

            if($ufa_api_withdraw['status'] === 'success') {

                $response = [
                    'status' => true
                ];

            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $ufa_api_withdraw['errorCode'].' : '.$ufa_api_withdraw['message']
                ]);
            }

        } else if($this->brand->game_id == 3) {
            //RachaCasino
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->brand->agent_username;

            $racha_bot_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $data['type'] = 3;

            $racha_bot_api_deposit = $racha_bot_api->transfer($data);

            if($racha_bot_api_deposit['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                    ]
                ];

            }

        } else if($this->brand->game_id == 5) {
            //Fastbet
            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->brand->agent_username;
            
            $fastbet_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $fastbet_api->withdraw($data);

            if($deposit['code'] == 0) {
                
                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 6) {
            //Uking
            $uking_api = new UkingApi();

            $uking_api->agent = $this->brand->agent_username;
            
            $uking_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $uking_api->withdraw($data);

            if($deposit['code'] == 0) {

                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 7) {
            //AmbApi
            $amb_api = new AmbApi();

            $amb_api->agent = $this->brand->agent_username;
            
            $amb_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $amb_api->withdraw($data);

            if($deposit['code'] == 0) {

                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 8) {

            $pg_api = new PgApi();

            $pg_api->agent = $this->brand->agent_username;

            $pg_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $pg_api_withdraw = $pg_api->withdraw($data);

            if($pg_api_withdraw['status']['code'] == 0) {
                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['status']['message']
                ]);
            }

        } else if($this->brand->game_id == 9) {
            //AmbApi
            $mvp_api = new MvpApi();

            $mvp_api->agent = $this->brand->agent_username;
            
            $mvp_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $mvp_api->withdraw($data);

            if($deposit['code'] == 0) {

                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 10) {
            //AmbApi
            $auto_api = new Auto456();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $auto_api->withdraw($data);

            if($deposit['code'] == 0) {

                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if($this->brand->game_id == 11) {
            //AmbApi
            $auto_api = new AmbFunApi();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $deposit = $auto_api->withdraw($data);

            if($deposit['code'] == 0) {

                $response['status'] = true;
            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $deposit['message']
                ]);
            }

        } else if ($this->brand->game_id == 12) {

            $amb_king_api = new AmbKingApi();

            $amb_king_api->agent = $this->brand->agent_username;

            $amb_king_api->hash = $this->brand->hash;

            $amb_king_api->key = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['amount'] = $data['amount'];

            $amb_king_api_withdraw = $amb_king_api->withdraw($data);

            if($amb_king_api_withdraw['code'] == 0) {
                $response['status'] = true;
            }

        }

        return $response;

    }

    //checkcredit
    public function credit($data) {

        $response = [
            'status' => false,
            'data' => []
        ];

        if($this->brand->game_id == 1) {
            // $agent = $this->brand->agents->where('agent_order', $data['agent_order'])->first();
            // if($data['agent_order'] == 2) {
        
                // echo $this->brand->server_api.'/server-api/gclub/?credit&username='.$this->brand->agent_username_2.'&password='.$this->brand->agent_password_2.'&user='.$data['username'];

            // } else {
                $agent = $this->brand->agents->where('agent_order', $data['agent_order'])->first();

                // echo $this->brand->server_api.'/server-api/gclub/?credit&username='.$agent->agent_username.'&password='.$agent->agent_password.'&user='.$data['username'];
        
                $gclub_api = json_decode(file_get_contents($this->brand->server_api.'/server-api/gclub/?credit&username='.$agent->agent_username.'&password='.$agent->agent_password.'&user='.$data['username']),true);

                // dd($gclub_api);

                // echo $this->brand->server_api.'/server-api/gclub/?credit&username='.$agent->agent_username.'&password='.$agent->agent_password.'&user='.$data['username'];

            // }

            // dd($gclub_api);

            if($gclub_api['status'] === true) {

                $response = [
                    'status' => true,
                    'data' => [
                        'credit' => $gclub_api['Balance']
                    ]
                ];

            }

        } else if($this->brand->game_id == 2) {

            $ufa_api = new UfaApi();

            $ufa_api->api_key = $this->brand->app_id;   

            $ufa_api->agent_username = $this->brand->agent_username;

            $ufa_api->agent_password = $this->brand->agent_password;

            $data['username'] = $data['username'];

            $ufa_api_credit = $ufa_api->credit($data);

            if($ufa_api_credit['status'] === 'success') {

                $response = [
                    'status' => true,
                    'data' => [
                        'credit' => $ufa_api_credit['current_credit']
                    ]
                ];

            } else {
                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $ufa_api_credit['status']['message']
                ]);
            }

        } else if($this->brand->game_id == 3) {
            //RachaCasino
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->brand->agent_username;

            $racha_bot_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $racha_bot_api_credit = $racha_bot_api->credit($data);

            if($racha_bot_api_credit['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        'credit' => $racha_bot_api_credit['response']['credit'],
                    ]
                ];

            }

        } else if($this->brand->game_id == 5) {
            //Fastbet
            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->brand->agent_username;
            
            $fastbet_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $credit = $fastbet_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $ufa_api_credit['message']
                ]);
                
            }

        } else if($this->brand->game_id == 6) {
            //Uking
            $uking_api = new UkingApi();

            $uking_api->agent = $this->brand->agent_username;
            
            $uking_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $credit = $uking_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $credit['message']
                ]);
                
            }

        } else if($this->brand->game_id == 7) {
            //Amb
            $amb_api = new AmbApi();

            $amb_api->agent = $this->brand->agent_username;
            
            $amb_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $credit = $amb_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $credit['message']
                ]);
                
            }

        } else if($this->brand->game_id == 8) {

            $pg_api = new PgApi();

            $pg_api->agent = $this->brand->agent_username;

            $pg_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $pg_api_credit = $pg_api->credit($data);

            if($pg_api_credit['status']['code'] == 0) {
                
                $response['status'] = true;
                $response['data']['credit'] = $pg_api_credit['data']['balance'];
                $response['data']['outstanding'] = $pg_api_credit['data']['outstanding'];
                
            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $pg_api_credit['status']['message']
                ]);
                
            }

        } else if($this->brand->game_id == 9) {
            //Amb
            $mvp_api = new MvpApi();

            $mvp_api->agent = $this->brand->agent_username;
            
            $mvp_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $credit = $mvp_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $credit['message']
                ]);
                
            }

        } else if($this->brand->game_id == 10) {
            //Amb
            $auto_api = new Auto456();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $credit = $auto_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $credit['message']
                ]);
                
            }

        } else if($this->brand->game_id == 11) {
            //Amb
            $auto_api = new AmbFunApi();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $credit = $auto_api->credit($data);

            if($credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $credit['result']['credit'];

            } else {

                BotLog::create([
                    'brand_id' => $this->brand->id,
                    'logs' => $credit['message']
                ]);
                
            }
        } else if ($this->brand->game_id == 12) {

            $amb_king_api = new AmbKingApi();

            $amb_king_api->agent = $this->brand->agent_username;

            $amb_king_api->hash = $this->brand->hash;

            $amb_king_api->key = $this->brand->app_id;

            $data['username'] = $data['username'];

            $amb_king_api_credit = $amb_king_api->credit($data);

            if($amb_king_api_credit['code'] == 0) {

                $response['status'] = true;

                $response['data']['credit'] = $amb_king_api_credit['data']['balance'];

                $response['data']['outstanding'] = abs($amb_king_api_credit['data']['outStandingAmt']['slot']);

            }

        }

        return $response;

    }

    //changepassword
    public function changePassword($data) {

        if($this->brand->game_id == 2) {

            $ufa_api = new UfaApi();

            $ufa_api->api_key = $this->brand->app_id;   

            $ufa_api->agent_username = $this->brand->agent_username;

            $ufa_api->agent_password = $this->brand->agent_password;

            $data['username'] = $data['username'];

            $ufa_api_change_password = $ufa_api->changePassword($data);

            if($ufa_api_change_password['status'] === 'success') {

                $response = [
                    'status' => true
                ];

            }

        } else if($this->brand->game_id == 3) {
            //RachaCasino
            $racha_bot_api = new RachaApi();

            $racha_bot_api->agent = $this->brand->agent_username;

            $racha_bot_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password_old'] = $data['password_old'];

            $data['password'] = $data['password'];

            $racha_bot_api_bet = $racha_bot_api->changePassword($data);

            if($racha_bot_api_bet['code'] === 200) {

                $response = [
                    'status' => true,
                    'data' => [
                        // 'bet' => $racha_bot_api_bet['creditRemain']
                    ]
                ];

            }

        } else if($this->brand->game_id == 5) {
            //Fastbet
            $response['status'] = false;

            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->brand->agent_username;
            
            $fastbet_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $credit = $fastbet_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;

        } else if($this->brand->game_id == 6) {
            //Uking
            $response['status'] = false;

            $uking_api = new UkingApi();

            $uking_api->agent = $this->brand->agent_username;
            
            $uking_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $credit = $uking_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;
            
        } else if($this->brand->game_id == 7) {

            $response['status'] = false;

            $amb_api = new AmbApi();

            $amb_api->agent = $this->brand->agent_username;
            
            $amb_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $credit = $amb_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;

        } else if($this->brand->game_id == 8) {

            $pg_api = new PgApi();

            $pg_api->agent = $this->brand->agent_username;

            $pg_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['newPassword'] = $data['password'];

            $pg_api_changePassword = $pg_api->changePassword($data);

            if($pg_api_changePassword['status']['code'] == 0) {
                $response['status'] = true;
            }

        } else if($this->brand->game_id == 7) {

            $response['status'] = false;

            $amb_api = new MvpApi();

            $amb_api->agent = $this->brand->agent_username;
            
            $amb_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $credit = $amb_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;

        } else if($this->brand->game_id == 10) {

            $response['status'] = false;

            $auto_api = new Auto456();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $credit = $auto_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;

        } else if($this->brand->game_id == 11) {

            $response['status'] = false;

            $auto_api = new AmbFunApi();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $credit = $auto_api->resetPassword($data);

            if($credit['code'] == 0) {

                $response['status'] = true;
                
            } 

            return $response;

        } else if ($this->brand->game_id == 12) {

            $amb_king_api = new AmbKingApi();

            $amb_king_api->agent = $this->brand->agent_username;

            $amb_king_api->hash = $this->brand->hash;

            $amb_king_api->key = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['password'] = $data['password'];

            $amb_king_api_password = $amb_king_api->changePassword($data);

            if($amb_king_api_password['code'] == 0) {

                $response['status'] = true;

            }

            return $response;

        }

    }

    public function winLossNew($data) {

        if($this->brand->game_id == 1) {
            //Gclub
            $brand_agents = BrandAgent::whereBrandId($this->brand->id)->get();

            foreach($brand_agents as $agent) {

                $result = json_decode(file_get_contents($this->brand->server_api."/server-api/gclub/?winloss&username=".$agent->agent_username."&password=".$agent->agent_password."&type=".$data['type']."&start_date=".$data['start_date']."&end_date=".$data['end_date'].""),true);

                // echo $this->brand->server_api."/server-api/gclub/?winloss&username=".$agent->agent_username."&password=".$agent->agent_password."&type=".$data['type']."&start_date=".$data['start_date']."&end_date=".$data['end_date'];

                // exit;
                

                if($result['status'] == true) {

                    foreach($result['data'] as $result) {

                        $result_user = json_decode(file_get_contents($this->brand->server_api."/server-api/gclub/?winloss_user&username=".$this->brand->agent_username."&password=".$this->brand->agent_password."&user=".$result["username"]),true);

                        foreach($result_user['data'] as $result_user) {

                            $customer_bet_detail = CustomerBetDetail::whereUsername($result_user['username'])->whereBetDate($result_user['time'])->first();

                            if(!$customer_bet_detail) {

                                CustomerBetDetail::create([
                                    'username' => $result['username'],
                                    'game' => $result_user['game'],
                                    'turn_over' => $result_user['turn_over'],
                                    'win_loss' => $result_user['win_loss'],
                                    'bet' => $result_user['bet'],
                                    'total' => $result_user['total'],
                                    'bet_date' => $result_user['time'],
                                ]);

                            }

                        }

                    }

                }

            }

        } else if($this->brand->game_id == 5) {

            //fastbet
            $api = new FastbetApi();
            
            $api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['refer_id'] = $data['refer_id'];

            $win_loss_new = $api->winLossNew($data);
            
        } else if($this->brand->game_id == 6) {

            //uking
            $api = new UkingApi();
            
            $api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['refer_id'] = $data['refer_id'];

            $win_loss_new = $api->winLossNew($data);
            
        } else if($this->brand->game_id == 7) {

            //ambbet
            $api = new AmbApi();
            
            $api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['refer_id'] = $data['refer_id'];

            $win_loss_new = $api->winLossNew($data);
            
        } else if($this->brand->game_id == 8) {

            //pgslot

        } else if($this->brand->game_id == 9) {

            //mvp
            $api = new MvpApi();
            
            $api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['refer_id'] = $data['refer_id'];

            $win_loss_new = $api->winLossNew($data);
        
        } else if($this->brand->game_id == 10) {

            //456auto
            $api = new Auto456();
            
            $api->app_id = $this->brand->app_id;

            $data['username'] = $data['username'];

            $data['refer_id'] = $data['refer_id'];

            $win_loss_new = $api->winLossNew($data);

        }

        return $win_loss_new;

    }

    //turnover
    public function winLoss($data) {

        if($this->brand->game_id == 1) {
            //Gclub
            $brand_agents = BrandAgent::whereBrandId($this->brand->id)->get();

            foreach($brand_agents as $agent) {

                $result = json_decode(file_get_contents($this->brand->server_api."/server-api/gclub/?winloss&username=".$agent->agent_username."&password=".$agent->agent_password."&type=".$data['type']."&start_date=".$data['start_date']."&end_date=".$data['end_date'].""),true);

                // dd($this->brand->server_api."/server-api/gclub/?winloss&username=".$agent->agent_username."&password=".$agent->agent_password."&type=".$data['type']."&start_date=".$data['start_date']."&end_date=".$data['end_date']);

                // exit;

                // dd($result);
                
                if($result['status'] == true) {

                    foreach($result['data'] as $result) {

                        $result_user = json_decode(file_get_contents($this->brand->server_api."/server-api/gclub/?winloss_user&username=".$this->brand->agent_username."&password=".$this->brand->agent_password."&user=".$result["username"]),true);

                        echo $this->brand->server_api."/server-api/gclub/?winloss_user&username=".$this->brand->agent_username."&password=".$this->brand->agent_password."&user=".$result["username"];

                        dd($result_user);

                        foreach($result_user['data'] as $result_user) {

                            $customer_bet_detail = CustomerBetDetail::whereUsername($result['username'])->whereBetDate($result_user['time'])->first();

                            if(!$customer_bet_detail) {

                                CustomerBetDetail::create([
                                    'username' => $result['username'],
                                    'game' => $result_user['game'],
                                    'turn_over' => $result_user['turn_over'],
                                    'win_loss' => $result_user['win_loss'],
                                    'bet' => $result_user['bet'],
                                    'total' => $result_user['total'],
                                    'bet_date' => $result_user['time'],
                                ]);

                            }

                        }

                    }

                }

            }

        } else if($this->brand->game_id == 5) {

            //Fastbet

            $fastbet_api = new FastbetApi();

            $fastbet_api->agent = $this->brand->agent_username;
            
            $fastbet_api->app_id = $this->brand->app_id;

            $result = $fastbet_api->winLoss();
            
            if($result['code'] == 0) {

                $duration_date = explode(' - ', $result['result']['durationDate']);

                $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[0]);

                $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[1]);

                $customer_bet = CustomerBet::whereUsername($win_loss['member'])->whereStartDate($start_date)->first();

                if(empty($customer_bet)) {

                    foreach($result['result']['dataList'] as $win_loss) {

                        CustomerBet::create([
                            'username' => $win_loss['member']['username'],
                            'turn_over' => $win_loss['amount'],
                            'win_loss' => $win_loss['memberWinLose'],
                            'bet' => $win_loss['validAmount'],
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                        ]);

                    }

                }

            }


        } else if($this->brand->game_id == 6) {

            //Uking
            $uking_api = new UkingApi();

            $uking_api->agent = $this->brand->agent_username;
            
            $uking_api->app_id = $this->brand->app_id;

            // $data['username'] = $data['username'];

            // $data['ref'] = $data['ref'];

            $result = $uking_api->winLoss($data);

            // dd($result);
            
            if($result['code'] == 0) {

                $duration_date = explode(' - ', $result['result']['durationDate']);

                $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[0]);

                $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[1]);

                foreach($result['result']['dataList'] as $win_loss) {

                    $customer_bet = CustomerBet::whereUsername($win_loss['member']['username'])->whereStartDate($start_date)->first();

                    if(empty($customer_bet)) {

                        CustomerBet::create([
                            'username' => $win_loss['member']['username'],
                            'turn_over' => $win_loss['amount'],
                            'win_loss' => $win_loss['memberWinLose'],
                            'bet' => $win_loss['validAmount'],
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                        ]);

                    }

                }

            }

        } else if($this->brand->game_id == 7) {

            //Ambbet
            $amb_api = new AmbApi();

            $amb_api->agent = $this->brand->agent_username;
            
            $amb_api->app_id = $this->brand->app_id;

            $result = $amb_api->winLoss();
            
            if($result['code'] == 0) {

                $duration_date = explode(' - ', $result['result']['durationDate']);

                $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[0]);

                $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[1]);

                foreach($result['result']['dataList'] as $win_loss) {

                    $customer_bet = CustomerBet::whereUsername($win_loss['member']['username'])->whereStartDate($start_date)->first();

                    if(empty($customer_bet)) {

                        CustomerBet::create([
                            'username' => $win_loss['member']['username'],
                            'turn_over' => $win_loss['amount'],
                            'win_loss' => $win_loss['memberWinLose'],
                            'bet' => $win_loss['validAmount'],
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                        ]);

                    }

                }

            }

        } else if($this->brand->game_id == 9) {

            //mvp
            $api = new MvpApi();
            
            $api->app_id = $this->brand->app_id;

            $result = $api->winLoss();
            
            if($result['code'] == 0) {

                $duration_date = explode(' - ', $result['result']['durationDate']);

                $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[0]);

                $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[1]);

                foreach($result['result']['dataList'] as $win_loss) {

                    $customer_bet = CustomerBet::whereUsername($win_loss['member']['username'])->whereStartDate($start_date)->first();

                    if(empty($customer_bet)) {

                        CustomerBet::create([
                            'username' => $win_loss['member']['username'],
                            'turn_over' => $win_loss['amount'],
                            'win_loss' => $win_loss['memberWinLose'],
                            'bet' => $win_loss['validAmount'],
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                        ]);

                    }

                }

            }
        
        }  else if($this->brand->game_id == 10) {

            //Ambbet
            $auto_api = new Auto456();

            $auto_api->agent = $this->brand->agent_username;
            
            $auto_api->app_id = $this->brand->app_id;

            $result = $auto_api->winLoss();
            
            if($result['code'] == 0) {

                $duration_date = explode(' - ', $result['result']['durationDate']);

                $start_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[0]);

                $end_date = Carbon::createFromFormat('d/m/Y H:i:s', $duration_date[1]);

                foreach($result['result']['dataList'] as $win_loss) {

                    $customer_bet = CustomerBet::whereUsername($win_loss['member']['username'])->whereStartDate($start_date)->first();

                    if(empty($customer_bet)) {

                        CustomerBet::create([
                            'username' => $win_loss['member']['username'],
                            'turn_over' => $win_loss['amount'],
                            'win_loss' => $win_loss['memberWinLose'],
                            'bet' => $win_loss['validAmount'],
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                        ]);

                    }

                }

            }

        }

    }

    public function winLossYesterday($data) {

        $brand_agents = BrandAgent::whereBrandId($this->brand->id)->get();

        if($this->brand->game_id == 1) {

            foreach($brand_agents as $agent) {

                $result = json_decode(file_get_contents("http://45.77.37.65/server-api/gclub/?winloss&username=".$agent->agent_username."&password=".$agent->agent_password."&type=".$data['type']."&start_date=".$data['start_date']."&end_date=".$data['end_date'].""),true);
// 
                if($result['status'] == true) {
    
                    foreach($result['data'] as $result) {

                        $customer_bet = CustomerBetTotal::whereUsername($result['username'])->whereStartDate($data['start_date'].' 11:00:00')->first();

                        if($customer_bet == null) {

                            CustomerBetTotal::create([
                                'username' => $result['username'],
                                'detail' => $result['detail'],
                                'bet_count' => $result['bet_count'],
                                'turn_over' => $result['turn_over'],
                                'win_loss' => $result['win_loss'],
                                'bet' => $result['bet'],
                                'start_date' => $data['start_date'].' 11:00:00',
                                'end_date' => $data['end_date'].' 10:59:59',
                            ]);
                            
                        }

                    }
    
                }

            }

        } else if($this->brand->game_id == 3) {

        }

    }

}
