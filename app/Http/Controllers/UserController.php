<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Policy;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CurrentPasswordRule;

class UserController extends Controller
{
    /**
     * User一覧
     */
    public function index(Request $request){
        // Todo:検索機能
        $users = User::query();
        
        $users = $users->orderby('id', 'asc')
            ->paginate(10)->withQueryString();
        
        $users = User::noEmail($users);

        return view('user.index', compact('users'));
    }

    /**
     * プロフィール画面表示
     */
    public function profile_show(User $user)
    {
        User::noEmail($user);
        return view('user.profile', compact('user'));
    }

    /**
     * プロフィール編集 画面表示
     */
    public function profile_edit(User $user)
    {
        $user = Auth::user();
        $user->session_style =  explode(',' ,$user->session_style);

        return view('user.profile_edit', compact('user'));
    }

    /**
     * プロフィール更新動作
     */
    public function profile_update(Request $request, User $user){
        $this->Validate($request, [            
            'image_icon' => ['image', 'mimes:jpeg,png', 'max:1024', 'nullable'],
            'nickname' => ['required','string', 'max:20'],
            'name' => ['required', 'string', 'min:6', 'max:20','regex:/^[a-zA-Z0-9]+$/'],
            'email' => ['required', 'email', 'unique:users,email,' . Auth::user()->email . ',email'],
            'gmpl' => ['required', 'in:GMのみ,PLのみ,GMより,PLより', 'string'],
            'session_style' => ['required', 'array'],
            'session_style.*'  => ['string', 'in:ボイスのみ,テキストのみ,半分テキスト'],
            'oneword' => ['string', 'max:40', 'nullable'],
            'comment' => ['string', 'max:400', 'nullable'],
            'now_password' => ['required', 'min:6' , 'max:20' , new CurrentPasswordRule(), 'nullable'],
            'password' => ['confirmed', 'min:6', 'max:20' , 'nullable'],
        ]);

        $data = [
            'nickname' => $request->nickname,
            'name' => $request->name,
            'gmpl' => $request->gmpl,
            'session_style' => $request->session_style,
            'email' => $request->email,
            'oneword' => $request->oneword,
            'comment' => $request->comment,
        ];
        
        // セッションスタイルを配列から文字列へ
        $data['session_style'] = implode(',' , $data['session_style']);

        // パスワードが入力されていれば、更新データに追加
        if($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        // 画像処理
        $file = $request->file('image_icon');
        $data['image_icon'] = User::uploadImage($file, 'icon');

        // 更新
        User::where('id', $user->id)
            ->update($data);

        return redirect()->route('users.profile.edit');
    }

    /**
     * アカウント削除
     */
    public function delete(Request $request, User $user){
        return ('まだ実装してない');
    }

}
