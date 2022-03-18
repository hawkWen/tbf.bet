<?php 

namespace App\Helpers;

use App\Helpers\BotApi;
use App\Helpers\LineApi;
use App\Helpers\UkingApi;

class Bot {

    public static function notifyRegister($brand,$customer) {

        $line_api = new LineApi();
                
        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $message1 = "ระบบได้สร้างไอดีเข้าเล่นให้ลูกค้าเรียบร้อย \n";

        $message1 .= "Username: ".$customer->username." \n";

        $message1 .= "Password: ".$customer->password_generate." \n";

        if($brand->game_id == 5) {

            $message1 .= 'ทางเข้าเล่น: https://fastbet98.com/#/';

        }
    
        $push = $line_api->pushMessage($customer->line_user_id, $message1);

    }

    public static function registerFastbet($brand,$bank_account,$name) {
        
        $response = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->type_api = $brand->type_api;

        $bot_api->agent_prefix = $brand->agent_prefix;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->agent_password = $brand->agent_password;

        $bot_api->username = substr($bank_account,-6);

        $bot_api->password = 'fb'.substr($bank_account,-6);

        $bot_api->contact = $name;

        $bot_api->amount = 0;

        $bot_api_register = $bot_api->register();

        return $response;
        
    }

    public static function registerRacha($brand, $bank_account, $name) {

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->app_id = $brand->app_id;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->username = $bank_account;

        $bot_api->password = $bank_account;

        $bot_api->name = $name;



    }

    public static function registerGclub($brand,$bank_account) {
        
        $response = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->type_api = $brand->type_api;

        $bot_api->agent_prefix = $brand->agent_prefix;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->agent_password = $brand->agent_password;

        $bot_api->username = substr($bank_account,-6);

        $bot_api->password = substr($bank_account,-6);

        $bot_api->telephone = $bank_account;

        $bot_api_register = $bot_api->register();

        if($bot_api_register['status'] == true) { 

            $response = ['status' => true];

        }

        return $response;

    }

    public static function registerUking($brand,$bank_account, $telephone, $name) {
        
        $response = [
            'status' => false,
            'data' => [
                'username' => null,
            ]
        ];

        $uking_api = new UkingApi();

        $uking_api->agent = $brand->agent_username;

        $uking_api->api_key = $brand->app_id;

        $data['username'] = substr($bank_account,-6);

        $data['password'] = 'uk'.substr($bank_account,-6);

        $data['telephone'] = $telephone;

        $data['name'] = $name;

        $register = $uking_api->create($data);

        if($register['code'] == 0) {
            $response['status'] = true;
            $response['data']['username'] = $register['result']['username'];
        }

        return $response;

    }

    public static function depositGclub($brand,$customer,$total_amount) {
        
        $response = [
            'status' => false,
        ];

        $response['online'] = false;

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        if($customer->username === NULL) {

            if($customer->agent_order == 2) {

                $bot_api->agent_username = $brand->agent_username_2;

                $bot_api->agent_password = $brand->agent_password_2;

            } else {

                $bot_api->agent_username = $brand->agent_username;

                $bot_api->agent_password = $brand->agent_password;

            }

            $bot_api->username = substr($customer->bank_account,-6);

            $bot_api->password = substr($customer->bank_account,-6);

            $bot_api->telephone = $customer->bank_account;

            $bot_api->amount = 0;

            $bot_api_register = $bot_api->register();

            if($bot_api_register['status'] === true) {

                $customer->update([
                    'username' => $bot_api_register['data']['username'],
                    'password' => bcrypt('gc'.substr($customer->bank_account,-6)),
                    'password_generate' => 'gc'.substr($customer->bank_account,-6),
                ]);

                if($brand->noty_register) {

                    Bot::notifyRegister($brand,$customer);
    
                }

                $response['status'] = true;

                if($customer->agent_order == 2) {
    
                    $bot_api->agent_username = $brand->agent_username_2;
    
                    $bot_api->agent_password = $brand->agent_password_2;
    
                } else {
    
                    $bot_api->agent_username = $brand->agent_username;
    
                    $bot_api->agent_password = $brand->agent_password;
    
                }

                $bot_api->username = $bot_api_register['data']['username'];

                $bot_api->amount  = $total_amount;

                $bot_api_deposit = $bot_api->deposit();

                if($bot_api_deposit['status'] === true) { 

                    $customer->update([
                        'status_deposit' => 1,
                    ]);

                    $response['status'] = true;

                    $response['online'] = false;

                } else {

                    $response['status'] = false;

                    $response['online'] = false;

                }

            } 

        } else {

            $response['status'] = false;

            if($customer->agent_order == 2) {

                $bot_api->agent_username = $brand->agent_username_2;

                $bot_api->agent_password = $brand->agent_password_2;

            } else {

                $bot_api->agent_username = $brand->agent_username;

                $bot_api->agent_password = $brand->agent_password;

            }

            $bot_api->username = $customer->username;

            $bot_api->amount  = $total_amount;

            $bot_api_deposit = $bot_api->deposit();

            if($bot_api_deposit['status'] === true) { 

                $customer->update([
                    'status_deposit' => 1,
                ]);

            }

            $response['status'] = $bot_api_deposit['status'];

            $response['online'] = $bot_api_deposit['data']['online'];

        }

        return $response;
        
    }

    public static function depositFastbet($brand,$customer,$total_amount) {
        
        $response = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->type_api = $brand->type_api;

        $bot_api->agent_prefix = $brand->agent_prefix;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->agent_password = $brand->agent_password;

        $bot_api->app_id = $brand->app_id;

        // if($customer->username === NULL || $customer->status_deposit == 0) {

        //     $bot_api->username = substr($customer->bank_account,-6);

        //     $bot_api->password = 'fb'.substr($customer->bank_account,-6);            

        //     $bot_api->contact = $customer->name;

        //     $bot_api->amount = $total_amount;

        //     $bot_api_register = $bot_api->register();

        //     if($bot_api_register['status'] == true) { 

        //         $customer->update([
        //             'username' => $brand->agent_prefix.substr($customer->bank_account,-6),
        //             'password' => \bcrypt('fb'.substr($customer->bank_account,-6)),
        //             'password_generate' => 'fb'.substr($customer->bank_account,-6),
        //             'status_deposit' => 1,
        //             'credit' => $total_amount,
        //             'last_update_credit' => date('Y-m-d H:i:s')
        //         ]);

        //         if($brand->noty_register) {

        //             Bot::notifyRegister($brand,$customer);
    
        //         }

        //         $bot_api_deposit = $bot_api->deposit();

        //         if($bot_api_deposit == true) {

        //             $response['status'] = true;

        //         }

        //     }

        // } else {

            $response['status'] = false;

            $bot_api->username = $customer->username;

            $bot_api->amount = $total_amount;

            $bot_api_deposit = $bot_api->deposit();

            if($bot_api_deposit['status'] == true) {

                $response = ['status' => true];

                $customer->update([
                ]); 

            }

        // }
// 
        return $response;

    }

    public static function depositUking($brand,$customer,$total_amount) {

        $response['status'] = false;

        $uking_api = new UkingApi();

        $uking_api->agent = $brand->agent_username;
        
        $uking_api->api_key = $brand->app_id;

        $data['username'] = $customer->username;

        $data['amount'] = $total_amount;

        $deposit = $uking_api->deposit($data);

        if($deposit['code'] == 0) {
            $response['status'] = true;
        }

        return $response;

    }

    public static function withdrawGclub($brand,$customer,$total_amount) {

        $response = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        if($customer->agent_order == 2) {

            $bot_api->agent_username = $brand->agent_username_2;

            $bot_api->agent_password = $brand->agent_password_2;

        } else {

            $bot_api->agent_username = $brand->agent_username;
    
            $bot_api->agent_password = $brand->agent_password;

        }

        $bot_api->username = $customer->username;

        $bot_api->amount = $total_amount;

        $bot_api_credit = $bot_api->withdraw();

        return $bot_api_credit;

    }

    public static function withdrawFastbet($brand,$customer,$total_amount) {

        $response = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->type_api = $brand->type_api;

        $bot_api->app_id = $brand->app_id;

        $bot_api->agent_prefix = $brand->agent_prefix;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->agent_password = $brand->agent_password;

        $bot_api->username = $customer->username;

        $bot_api->amount = $total_amount;

        $bot_api_credit = $bot_api->withdraw();

        return $bot_api_credit;

    }

    public static function withdrawUking($brand,$customer,$total_amount) {

        $response['status'] = false;

        $uking_api = new UkingApi();

        $uking_api->agent = $brand->agent_username;
        
        $uking_api->api_key = $brand->app_id;

        $data['username'] = $customer->username;

        $data['amount'] = $total_amount;

        $deposit = $uking_api->withdraw($data);

        if($deposit['code'] == 0) {
            $response['status'] = true;
        }

        return $response;

    }

    public static function creditGclub($brand,$customer) {

        $response = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;
        
        if($customer->agent_order == 2) {

            $bot_api->agent_username = $brand->agent_username_2;

            $bot_api->agent_password = $brand->agent_password_2;

        } else {

            $bot_api->agent_username = $brand->agent_username;
    
            $bot_api->agent_password = $brand->agent_password;

        }

        $bot_api->username = $customer->username;

        $bot_api_credit = $bot_api->checkCredit();

        if($bot_api_credit['status'] === true) {

            $response['status'] = true;

            $response['data']['credit'] = $bot_api_credit['data']['credit'];

            $customer->update([
                'credit' => $bot_api_credit['data']['credit'],
                'last_update_credit' => date('Y-m-d H:i:s'),
            ]);

        }

        return $response;

    }

    public static function creditFastbet($brand,$customer) {

        $resposne = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->type_api = $brand->type_api;

        $bot_api->agent_prefix = $brand->agent_prefix;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->agent_password = $brand->agent_password;

        $bot_api->username = $customer->username;

        $bot_api->app_id = $brand->app_id;

        $bot_api_credit = $bot_api->checkCredit();

        if($bot_api_credit['status'] === true) {

            $response['status'] = true;

            $response['data']['credit'] = $bot_api_credit['data']['credit'];


        }

        return $response;

    }

    public static function creditUking($brand,$customer) {

        $response['status'] = false;

        $uking_api = new UkingApi();

        $uking_api->agent = $brand->agent_username;
        
        $uking_api->api_key = $brand->app_id;

        $data['username'] = $customer->username;

        $credit = $uking_api->credit($data);

        if($credit['code'] == 0) {

            $response['status'] = true;

            $response['result']['credit'] = $credit['result']['credit'];

            $customer->update([
                'credit' => $credit['result']['credit'],
                'last_update_credit' => date('Y-m-d H:i:s'),
            ]);
            
        } 

        return $response;

    }

    public static function changePasswordFastbet($brand,$username,$password) {

        $resposne = [
            'status' => false,
        ];

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->type_api = $brand->type_api;

        $bot_api->agent_prefix = $brand->agent_prefix;

        $bot_api->agent_username = $brand->agent_username;

        $bot_api->agent_password = $brand->agent_password;

        $bot_api->app_id = $brand->app_id;

        $bot_api->username = $username;

        $bot_api->password = $password;

        $bot_api_password = $bot_api->resetPassword();

        return $bot_api_password;

    }

    public static function changePasswordUking($brand,$username,$password) {

        $response['status'] = false;

        $uking_api = new UkingApi();

        $uking_api->agent = $brand->agent_username;
        
        $uking_api->api_key = $brand->app_id;

        $data['username'] = $customer->username;

        $data['password'] = $customer->password;

        $credit = $uking_api->resetPassword($data);

        if($credit['code'] == 0) {

            $response['status'] = true;
            
        } 

        return $response;
    }

    public static function loginSaGame($brand,$customer) {

        $response['status'] = false;

        $uking_api = new UkingApi();

        $uking_api->agent = $brand->agent_username;

        $uking_api->api_key = $brand->app_id;

        $data['username'] = $customer->username;

        $data['password'] = $customer->password_generate;

        $login_sa = $uking_api->loginSaGaming($data);

        $customer->update([
            'line_menu_member' => $login_sa["url"],
        ]);

    }

}