<?php

namespace App\Http\Controllers\Uking;

use App\Helpers\Bot;
use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\BotApi;
use App\Helpers\Helper;
use App\Models\Customer;
use App\Helpers\FastbetApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    //
    use AuthenticatesUsers;

    protected $redirectTo = '/member';

    public function logout(Request $request, $brand)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/'.$brand.'/member/login');
    }

    public function showRegisterForm($brand,$invite_id)
    {

        $customer_invite = null;

        if($invite_id != 0) {
            $customer_invite = Customer::find($invite_id);
        } 

        $banks = Bank::all();
    
        $brand = Brand::whereSubdomain($brand)->first();

        return view('uking.members.register',compact('banks','brand','customer_invite'));
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        // $credentials['status'] = 1;

        return $credentials;
    }


    public function showLoginForm($brand) {

        $brand = Brand::whereSubdomain($brand)->first();
    
        if(Auth::guard('customer')->check()) {
            return redirect()->route('uking.member', $brand->subdomain);
        }

        return view('uking.members.login',compact('brand'));


    }

    public function registerCheck(Request $request) {

        $input = $request->all();

    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $login = $this->attemptLogin($request);

        if ($this->attemptLogin($request)) {

            $ip = Helper::getUserIpAddr();

            $customer = Customer::whereUsername($request['username'])->first();
            
            $customer->update([
                'last_login' => date('Y-m-d H:i:s'),
                'browser' => Helper::detectUserBrowser(),
                'operation' => Helper::detectUserOS(),
                'ip' => Helper::getIPLocation(),
            ]);

            // return view('');

            return $this->sendLoginResponse($request,$customer->brand->subdomain);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();

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

        if($input['code_bank'] === 'SCB') {

            $input['bank_account_scb'] = substr($input['bank_account'],-4);

        } else {

            $input['bank_account_scb'] = substr($input['bank_account'],-6);

        }
    
        $input['bank_account_krungsri'] = substr($input['bank_account'],3);
    
        $input['bank_account_kbank'] = substr(substr($input['bank_account'],3),0,-1);

        $register_response = Bot::registerUking($brand,$input['bank_account'],$input['telephone'],$input['name']);

        if($register_response['status'] === true) {

            $input['username'] = $register_response['data']['username'];

            $input['password'] = bcrypt('uk'.substr($input['bank_account'],-6));

            $input['password_generate'] = 'uk'.substr($input['bank_account'],-6);

            $input['status_deposit'] = 0;
 
            $customer = Customer::create($input);
    
            $login = Auth::guard('customer')->loginUsingId($customer->id);

        } else {

            DB::rollback();

            return \redirect()->back()->withErrors(['uking ปิดปรับปรุง ขออภัยในความไม่สะดวกนะคะ']);

        }

        DB::commit();

        \Session::flash('alert-success', 'สมัครสมาชิกเรียบร้อยขอบคุณค่ะ _/\_');

        return redirect()->route('uking.member', $brand->subdomain);
        
    }

    protected function sendLoginResponse(Request $request,$subdomain)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            echo '1';
            return $response;
        }

        // echo "<script>window.top.location.href = \"http://uking.casinoauto.io/sa517/member\";</script>";


        return redirect()->route('uking.member', $subdomain);
    }

    protected function attemptLogin(Request $request)
    {
        $attemp = Auth::guard('customer')->attempt(
            $this->credentials($request), $request->filled('remember')
        );

        return $attemp;
    }

    public function checkPhone(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereBrandId($input['brand_id'])->get();

        $telephone = $customer->where('telephone', '=', $input['telephone'])->count();

        DB::commit();

        return response()->json([
            'count' => $telephone,
        ]);

    }

    public function checkBank(Request $request) {

        $input = $request->all();

        DB::beginTransaction();

        $bank = explode(':',$input['bank_id']);

        $input['bank_id'] = $bank[0];

        $input['code_bank'] = $bank[1];

        $response = false;

        if($input['code_bank'] === 'SCB') {

            $input['bank_account_scb'] = substr($input['bank_account'],-4);

            $customer = Customer::whereBrandId($input['brand_id'])->whereBankId(1)->where('bank_account_scb','=', $input['bank_account_scb'])->count();

            

            if($customer > 0) {
                
                $response = false;
            } else {
                
                $response = true;
            }

        } else {

            $input['bank_account_scb'] = substr($input['bank_account'],-6);

            $customer = Customer::whereBrandId($input['brand_id'])->where('bank_account_scb','=', $input['bank_account_scb'])->count();

            

            if($customer > 0) {
                $response = false;
            } else {
                $response = true;
            }

        }

        $brand = Brand::find($input['brand_id']);

        $username = $brand->agent_prefix.substr($input['bank_account'],-6);

        $password = 'gc'.substr($input['bank_account'],-6);

        DB::commit();

        return response()->json([
            'count' => $response,
            'username' => $username,
            'password' => $password,
        ]);

    }

    public function guard()
    {
        return Auth::guard('customer');
    }

    public function username() {
        return 'username';
    }
}
