<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Policy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * User一覧
     */
    public function index(Request $request){

        $users = User::orderby('id', 'asc')
            ->paginate(10);
        
        foreach($users as $user){
            User::admin($user);
        }

        return view('user.index', compact('users'));
    }

    /**
     * Admin編集画面
     */
    public function admin_edit($id){
        $user = User::find($id);
        return view('user.admin', ['user' => $user]);
    }

    /**
     * Admin編集動作
     */
    public function admin_update(Request $request, User $user){
        // TODO:まずバリデートしろよ！
        // 'admin' => ['required', 'in:商品,掲示板,全て'],

        // ユーザーがどの権限を持ってるかの判定
        $user['admin']= User::admin($user);
        
        // 変更されてたらupdate
        if($user->admin !== $request->admin){
            // 最後にupdate()に代入するための変数。一般はこれ
            $updateData = [
                'item_admin' => null,
                'theread_admin' => null,
            ];

            // adminに入ってる値によって入れるところを変える。
            if($request->admin === '商品'){
                $updateData['item_admin'] = 1;
            } elseif($request->admin === '掲示板'){
                $updateData['theread_admin'] = 1;
            } elseif($request->admin === '全て'){
                $updateData = [
                    'item_admin' => 1,
                    'theread_admin' => 1,                    
                ];
            }

            // データの更新
            Policy::where('user_id', $user->id)
                ->update($updateData);

            $message = $user->name . 'さんの権限を' . $request->admin . 'に変更しました。';
            return redirect('users/')
                ->with('admin_message', $message);
        }

        // されてなかったらredirectして一覧に戻る。
        return redirect('users/');
    }

    /**
     * アカウント削除
     */
    public function delete(Request $request, User $user){
        return ('まだ実装してない');
    }

}
