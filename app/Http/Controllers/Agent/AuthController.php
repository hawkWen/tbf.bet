<?php

namespace App\Http\Controllers\Agent;

use App\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    
    //
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function showLoginForm() {
        
        return view('agent.login');
    }

    public function username() {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');

        $credentials['status'] = 1;

        return $credentials;
    }

    protected function sendLoginResponse(Request $request)
    {
        $rules = ['captcha' => 'required|captcha'];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['Invalid Captcha']);
        } 
        
        $request->session()->regenerate();
        $previous_session = Auth::user()->last_session;
        if ($previous_session) {
            $debug = Session::getHandler()->destroy($previous_session);
        }
        Auth::user()->last_session = Session::getId();
        Auth::user()->save();
        $this->clearLoginAttempts($request);
        return $this->authenticated($request, Auth::user())
            ?: redirect()->intended($this->redirectPath());
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

        if ($this->attemptLogin($request)) {
            $user = User::whereUsername($request->username)->first();
            $user->update([
                'last_login' => date('Y-m-d H:i:s'),
                'browser' => Helper::detectUserBrowser(),
                'operation' => Helper::detectUserOS(),
                'ip' => Helper::getIPLocation(),
            ]);
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
