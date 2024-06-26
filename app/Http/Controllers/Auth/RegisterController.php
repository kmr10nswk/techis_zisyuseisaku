<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Policy;
use App\Models\Admin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web');
        $this->middleware('guest:admin')->except(['showAdminRegisterForm', 'registerAdmin']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nickname' => ['required','string', 'max:20'],
            'name' => ['required', 'string', 'min:6', 'max:20','regex:/^[a-zA-Z0-9]+$/', 'unique:users'],
            'gmpl' => ['required', 'in:GMのみ,PLのみ,GMより,PLより', 'string'],
            'session_style' => ['required', 'array'],
            'session_style.*'  => ['string', 'in:ボイスのみ,テキストのみ,半テキ'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $data['session_style'] = implode(',' , $data['session_style']);

        return User::create([
            'nickname' => $data['nickname'],
            'name' => $data['name'],
            'gmpl' => $data['gmpl'],
            'session_style' => $data['session_style'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'image_icon' => 'icon/default_icon_1.png',
        ]);
    }

    /**
     * 管理者アカウント作成用
     */
    protected function adminValidator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string' , 'max:20'],
            'admin' => ['required', 'in:商品,掲示板,全て'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
        ]);
    }

    public function showAdminRegisterForm()
    {
        return view('auth.admin_register', ['authgroup' => 'admin']);
    }

    public function registerAdmin(Request $request)
    {
        $this->adminValidator($request->all())->validate();

        event(new Registered($user = $this->createAdmin($request->all())));
        if(!Auth::guard('admin')->check()){
            Auth::guard('admin')->login($user);
        }

        if($response = $this->registeredAdmin($request, $user)){
            return $response;
        }
        if(Auth::guard('admin')->check()){
            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect(route('admins.index'));
        }
        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect(route('home'));
    }

    protected function createAdmin(array $data)
    {
        $register_admin = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $create_policy = [
            'admin_id' => $register_admin->id,
            'item_admin' => null,
            'theread_admin' => null,
        ];

        // adminに入ってる値によって入れるところを変える。
        if($data['admin'] === '商品'){
            $create_policy['item_admin'] = 1;
        } elseif($data['admin'] === '掲示板'){
            $create_policy['theread_admin'] = 1;
        } elseif($data['admin'] === '全て'){
            $create_policy['item_admin'] = 1;
            $create_policy['theread_admin'] = 1;
        }
        
        Policy::create($create_policy);

        return $register_admin;
    }

    protected function registeredAdmin(Request $request, $user)
    {
        // 
    }
}
