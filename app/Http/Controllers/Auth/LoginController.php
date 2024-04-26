<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     * ログアウト機能を除いて、login機能を使う時は必ずlogin済でないことを確認
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'name' => 'required_without:'.$this->username(),
            $this->username() => 'required_without:name',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if(empty($request->name)){
            return $request->only($this->username(), 'password');
        }
        
        return $request->only('name', 'password');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        if($this->guard()->attempt($credentials, $request->boolean('remember'))){
            $user = $this->guard()->user();
            if($user->status === 'deleted'){
                $this->guard()->logout();
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 管理者ログイン用
     */
    public function showAdminLoginForm()
    {
        return view('auth.login', ['authgroup' => 'admin']);
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // クラスがThrottlesLoginsトレイトを使用している場合、自動的にスロットルを調整できます
        // このアプリケーションのログイン試行。これにユーザー名をキーとして入力します。
        // このアプリケーションに対してこれらのリクエストを行うクライアントのIPアドレスを記録。
        if (method_exists($this, 'hasTooManyLoginAttempts') && 
            $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if(Auth::guard('admin')->attempt([
            'email' => $request->email, 
            'password' => $request->password,
            ],
            $request->get('remember')))
        {
            if(Auth::guard('admin')->user()->deleted_at){                
                Auth::guard('admin')->logout();
                return back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'auth.failed',]);
            }
            return redirect()->intended('/');
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return back()->withInput($request->only('email', 'remember'))->withErrors(['email' => 'auth.failed',]);
    }


}
