<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Policy;
use App\Models\Admin;

class AdminControllers extends Controller
{
    /**
     * Admin一覧
     */
    public function index(){
        // 権限検索とかもつけたい


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
        // バリデーション
        $this->validate($request, [
            'admin' => ['required', 'in:商品,掲示板,全て'],
        ]);

        // ユーザーがどの権限を持ってるかの判定
        $user['admin'] = User::admin($user);
        
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

        // 変更されてなかったらredirectして一覧に戻る。
        return redirect('users/');
    }

}
