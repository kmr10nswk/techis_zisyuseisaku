<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Policy;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $this->middleware('guest');
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
            'name' => ['required', 'string', 'min:6', 'max:20','regex:/^[a-zA-Z0-9]+$/'],
            'sex' => ['required', 'in:男,女,その他', 'string'],
            'age' => ['required', 'numeric', 'between:18,90'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image_icon' => ['image', 'mimes:jpeg,png', 'max:1024'],
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
        $register_user= User::create([
            'nickname' => $data['nickname'],
            'name' => $data['name'],
            'sex' => $data['sex'],
            'age' => $data['age'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'image_icon' => $data['image_icon'],
        ]);

        Policy::create([
            'user_id' => $register_user->id,
            'ippan_admin' => 1,
        ]);

        return $register_user;
    }
}
