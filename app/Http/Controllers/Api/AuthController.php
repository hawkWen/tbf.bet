<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Api;
use App\Helpers\Bot;
use App\Models\Brand;
use App\Helpers\BotApi;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Helpers\RachaApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
        return auth()->shouldUse('api-customer');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $input['telephone'] = str_replace('_','',$input['telephone']);

        if($input['type_login'] === 1) {

            $customer = Customer::whereTelephone($input['telephone'])->whereBrandId($input['brand_id'])->first();

        } else {

            $customer = Customer::whereUsername($input['username'])->whereBrandId($input['brand_id'])->first();

        }

        if(!$customer) {
            return response()->json([
                'message' => 'ชื่อผู้ใชงานหรือรหัสผ่านผิดพลาดกรุณาตรวจสอบ'
            ], 401);
        }

        if(!Hash::check($input['password'], $customer->password)) {

            return response()->json([
                'message' => 'ชื่อผู้ใชงานหรือรหัสผ่านผิดพลาดกรุณาตรวจสอบ'
            ], 401);
            
        }
        
        $customer->update([
            'last_login' => date('Y-m-d H:i:s'),
            'browser' => Helper::detectUserBrowser(),
            'operation' => Helper::detectUserOS(),
            'ip' => Helper::getIPLocation(),
        ]);

        $token = auth()->login($customer);
        
        return $this->respondWithToken($token, '');
    }

    public function serviceLogin(Request $request) {

        $input = $request->all();

        $brand = Brand::whereAgentPrefix($input['agent_prefix'])->first();

        if($input['type_login'] === 1) {

            $customer = Customer::whereTelephone($input['username'])->whereBrandId($brand->id)->first();

        } else {

            $customer = Customer::whereUsername($input['username'])->whereBrandId($brand->id)->first();

        }

        if(!$customer) {
            return response()->json([
                'message' => 'ชื่อผู้ใชงานหรือรหัสผ่านผิดพลาดกรุณาตรวจสอบ'
            ], 401);
        }

        if(!Hash::check($input['password'], $customer->password)) {

            return response()->json([
                'message' => 'ชื่อผู้ใชงานหรือรหัสผ่านผิดพลาดกรุณาตรวจสอบ'
            ], 401);
            
        }

        $token = auth()->login($customer);

        $url_redirect = 'https://casinoauto.io/'.$brand->subdomain.'/login/service?token='.$token;

        return $this->respondWithToken($token,$url_redirect);

    }
    
    public function register(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $brand = Brand::find($input['brand_id']);

        $input['game_id'] = $brand->game_id;

        $input['status'] = 0;

        $bank = explode(':',$input['bank_id']);

        $input['bank_id'] = $bank[0];

        $input['code_bank'] = $bank[1];

        $input['name'] = $input['fname'].' '.$input['lname'];

        $input['password_generate'] = $input['password'];

        $input['password'] = \bcrypt($input['password']);

        $input['bank_account'] = \str_replace('-','',$input['bank_account']);

        $input['bank_account'] = \str_replace('_','',$input['bank_account']);

        if($input['code_bank'] === 'SCB') {

            $input['bank_account_scb'] = substr($input['bank_account'],-4);

        } else {

            $input['bank_account_scb'] = substr($input['bank_account'],-6);

        }
    
        $input['bank_account_krungsri'] = substr($input['bank_account'],3);
    
        $input['bank_account_kbank'] = substr(substr($input['bank_account'],3),0,-1);

        $api = new Api($brand);

        $data['name'] = ($brand->game_id == 1) ? str_replace('-','',$input['bank_account_scb']) : $input['name'];

        $data['contact'] = $input['telephone'];

        if($brand['status_telephone'] == 1) {

            if($brand->id == 21) {

                $data['username'] = substr($input['telephone'],-6);

            } else {

                $data['username'] = substr($input['telephone'],-6);

            }

            $data['password'] = $input['password_generate'];

        } else {

            if($brand->id == 21) {

                $data['username'] = rand(0,9).substr($input['bank_account'],-6);

            } else {

                $data['username'] = rand(0,9).substr($input['bank_account'],-6);

            }

            $data['password'] = $input['password_generate'];

        }

        $data['agent_order'] = $brand->agent_order;

        $input['agent_order'] = $brand->agent_order;

        $api_register = $api->register($data);

        if($api_register['status'] === true) {

            $input['username'] = $api_register['data']['username'];

            $input['password'] = $input['password'];

            $input['password_generate'] = $input['password_generate'];

            // $input['telephone'] = Helper::encryptString($input['telephone'],1,'base64');

            // $input['line_id'] = Helper::encryptString($input['line_id'],1,'base64');

            $input['status_deposit'] = 1;
 
            $customer = Customer::create($input);

        } else {

            DB::rollback();

            return response()->json([
                'message' => 'ลองใหม่อีกครั้งค่ะ'
            ], 401);

        }

        DB::commit();

        return response()->json([
            'message' => 'สมัครสมาชิกใหม่เรียบร่อย',
            'data' => $customer
        ], 200);

    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $customer = Customer::find(auth()->user()->id);

        $brand = Brand::find($customer->brand_id);

        $api = new Api($brand);

        $data['username'] = $customer->username;

        $data['agent_order'] = $customer->agent_order;

        $api_credit = $api->credit($data);

        if($api_credit['status'] == true) {

            $credit['data']['credit'] = $api_credit['data']['credit'];

            $customer->update([
                'credit' => $credit['data']['credit']
            ]);

        }

        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }   

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$url_redirect)
    {
        return response()->json([
            'token' => $token,
            'user' => $this->guard()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60 * 60 * 7,
            'url_redirect' => $url_redirect
        ]);
    }

    public function guard() {
        return Auth::guard('api-customer');
    }
}
