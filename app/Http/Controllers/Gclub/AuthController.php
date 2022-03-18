<?php

namespace App\Http\Controllers\Gclub;

use App\Models\Bank;
use App\Models\Brand;
use App\Helpers\BotApi;
use App\Helpers\Helper;
use App\Helpers\LineApi;
use App\Models\Customer;
use App\Helpers\FastbetApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\MemberRegisterRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    //
    use AuthenticatesUsers;

    // protected $redirectTo = '/member';

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
            : redirect('/' . $brand . '/member/login');
    }

    public function invite($brand)
    {

        $customer = Auth::guard('customer')->user();

        $brand = Brand::whereSubdomain($brand)->first();

        return view('gclub.members.invite', compact('brand', 'customer'));
    }

    public function showRegisterForm($brand, $invite_id)
    {

        $customer_invite = null;

        if ($invite_id != 0) {
            $customer_invite = Customer::find($invite_id);
        }

        $banks = Bank::where('id', '!=', 0)->get();

        $brand = Brand::whereSubdomain($brand)->first();

        return view('gclub.members.register', compact('banks', 'brand', 'customer_invite'));
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        // $credentials['status'] = 1;

        return $credentials;
    }


    public function showLoginForm($brand)
    {

        $brand = Brand::whereSubdomain($brand)->first();

        if (Auth::guard('customer')->check()) {
            return redirect()->route('gclub.member', $brand->subdomain);
        }

        return view('gclub.members.login', compact('brand'));
    }

    public function registerCheck(Request $request)
    {

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

            return $this->sendLoginResponse($request, $customer->brand->subdomain);
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

        // if($brand->status_telephone == 1) {

        //     $check_telephone = Customer::whereBrandId($brand->id)->whereTelephone($input['telephone'])->get();

        //     if($check_telephone->count() != 0) {

        //         return redirect()->back()->withErrors(['เบอร์โทรศัพท์​ซ้ำกับในระบบกรุณาติดต่อเจ้าหน้าที่']);

        //     }

        // }

        $input['game_id'] = $brand->game_id;

        $input['status'] = 0;

        $bank = explode(':', $input['bank_id']);

        $input['bank_id'] = $bank[0];

        $input['code_bank'] = $bank[1];

        $input['name'] = $input['fname'] . ' ' . $input['lname'];

        $input['password_generate'] = $input['password'];

        $input['password'] = \bcrypt($input['password']);

        if ($input['code_bank'] === 'SCB') {

            $input['bank_account_scb'] = substr($input['bank_account'], -4);
        } else {

            $input['bank_account_scb'] = substr($input['bank_account'], -6);
        }

        $input['bank_account_krungsri'] = substr($input['bank_account'], 3);

        $input['bank_account_kbank'] = substr(substr($input['bank_account'], 3), 0, -1);

        $bot_api = new BotApi();

        $bot_api->brand_id = $brand->id;

        if ($brand->agent_order == 2) {

            $input['agent_order'] = 2;

            $bot_api->agent_username = $brand->agent_username_2;

            $bot_api->agent_password = $brand->agent_password_2;
        } else {

            $bot_api->agent_username = $brand->agent_username;

            $bot_api->agent_password = $brand->agent_password;
        }

        $bot_api->game_id = $brand->game_id;

        $bot_api->server_api = $brand->server_api;

        $bot_api->app_id = $brand->app_id;

        if ($brand['status_telephone'] == 1) {

            $bot_api->username = substr($input['telephone'], -6);

            $bot_api->password = $input['password_generate'];

            $bot_api->telephone = $input['telephone'];
        } else {

            $bot_api->username = substr($input['bank_account'], -6);

            $bot_api->password = $input['password_generate'];

            $bot_api->telephone = $input['bank_account'];
        }

        $bot_api_register = $bot_api->register();

        if ($bot_api_register['status'] === true) {

            $input['username'] = $brand->agent_prefix . $bot_api->username;

            $input['password'] = $input['password'];

            $input['password_generate_2'] = $input['password_generate'];

            $input['password_generate'] = $input['password_generate'];

            $input['status_deposit'] = 1;

            $customer = Customer::create($input);

            $login = Auth::guard('customer')->loginUsingId($customer->id);
        } else {

            DB::rollback();

            return \redirect()->back()->withErrors(['GCLUB ปิดปรับปรุง เวลา 10.00 ถึง 11.30 โดยประมาณ ขออภัยในความไม่สะดวกนะคะ']);
        }

        DB::commit();

        \Session::flash('alert-success', 'สมัครสมาชิกเรียบร้อยขอบคุณค่ะ _/\_');

        return redirect()->route('gclub.member.welcome', $brand->subdomain);
    }

    protected function sendLoginResponse(Request $request, $subdomain)
    {
        $request->session()->regenerate();
        $previous_session = Auth::guard('customer')->user()->last_session;
        if ($previous_session) {
            $debug = Session::getHandler()->destroy($previous_session);
        }

        Auth::guard('customer')->user()->last_session = Session::getId();
        Auth::guard('customer')->user()->save();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard('customer')->user())
            ?: redirect()->route('gclub.member', $subdomain);
    }

    protected function attemptLogin(Request $request)
    {
        $attemp = Auth::guard('customer')->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );

        return $attemp;
    }

    public function checkPhone(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereBrandId($input['brand_id'])->get();

        $telephone = $customer->where('telephone', '=', $input['telephone'])->count();

        DB::commit();

        return response()->json([
            'count' => $telephone,
        ]);
    }

    public function redirectTo($brand)
    {

        $brand = Brand::whereSubdomain($brand)->first();

        return view('gclub.redirect', compact('brand'));
    }

    public function checkBank(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $bank = explode(':', $input['bank_id']);

        $input['bank_id'] = $bank[0];

        $input['code_bank'] = $bank[1];

        $response = false;

        $bank_account_unqiue = Customer::whereBrandId($input['brand_id'])->where('bank_account', '=', $input['bank_account'])->count();

        if ($bank_account_unqiue > 0) {
            $response = false;
        } else {
            $response = true;
        }

        $brand = Brand::find($input['brand_id']);

        $username = $brand->agent_prefix . substr($input['bank_account'], -6);

        $password = 'gc' . substr($input['bank_account'], -6);

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

    public function username()
    {
        return 'username';
    }

    public function connectLine($brand)
    {

        $brand = Brand::whereSubdomain($brand)->first();

        return view('gclub.members.connect-line', compact('brand'));
    }

    public function connectLineStore(Request $request)
    {

        $input = $request->all();

        DB::beginTransaction();

        $customer = Customer::whereUsername($input['username'])->first();

        if (!$customer) {

            return response()->json([
                'status' => false,
                'msg' => 'ไม่พบไอดีนี้ในเกมส์ กรุณาสมัครสมาชิกก่อน ค่ะ'
            ]);
        }

        $customer->update([
            'line_user_id' => $input['line_user_id'],
            'img_url' => $input['picture']
        ]);

        $brand = Brand::find($customer->brand_id);

        $rich_menu_id = $brand->line_menu_member;

        $token = $brand->line_token;

        $channel_secret = $brand->line_channel_secret;

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($token);

        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);

        $response = $bot->linkRichMenu($input['line_user_id'], $rich_menu_id);

        $line_api = new LineApi();

        $line_api->token = $brand->line_token;

        $line_api->channel_secret = $brand->line_channel_secret;

        $message1 = "ขอบคุณที่กดปุ่มกระดิ่งแจ้งเตือนกับเรานะคะ \n";

        $message1 .= "Username: " . $customer->username . " \n";

        $message1 .= "Password: " . $customer->password_generate . " \n";

        $message1 .= "กดเติมเงินได้ที่เมนูเติมเงินเลยนะคะ \n";

        if ($brand->game_id == 5) {

            $message1 .= 'ทางเข้าเล่น: https://fastbet98.com/#/';
        }

        $push = $line_api->pushMessage($customer->line_user_id, $message1);

        DB::commit();

        return \response()->json([
            'status' => true,
            'msg' => 'เชื่อมต่อกับบัญชี LINE เรียบร้อยขอบคุณค่ะ'
        ]);
    }
}
