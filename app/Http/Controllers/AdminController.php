<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Admin一覧
     */
    public function index(Request $request){
        // Todo:検索機能
        $admins = Admin::query();

        $admins = $admins->orderby('id', 'asc')
            ->paginate(10)->withQueryString();
        
        foreach($admins as $admin){
            $admin['policy_name'] = Admin::policyType($admin);
        }

        return view('admin.index', compact('admins'));
    }
    
    /**
     * Admin編集画面
     */
    public function edit($id){
        $admin = Admin::find($id);
        $admin['policy_name'] = Admin::policyType($admin);
        return view('admin.edit', compact('admin'));
    }

    /**
     * Admin編集動作
     */
    public function update(Request $request, Admin $admin){
        // バリデーション
        $this->validate($request, [
            'policy_name' => ['required', 'in:商品,掲示板,全て'],
        ]);
        
        // 権限名を付与
        $admin['policy_name'] = Admin::policyType($admin);
        
        // 変更されてたらupdate
        if($admin->policy_name !== $request->policy_name){
            // 最後にupdate()に代入するための変数。一般はこれ
            $updateData = [
                'item_admin' => null,
                'theread_admin' => null,
            ];

            // adminに入ってる値によって入れるところを変える。
            if($request->policy_name === '商品'){
                $updateData['item_admin'] = 1;
            } elseif($request->policy_name === '掲示板'){
                $updateData['theread_admin'] = 1;
            } elseif($request->policy_name === '全て'){
                $updateData = [
                    'item_admin' => 1,
                    'theread_admin' => 1,                    
                ];
            }

            // データの更新
            Policy::where('admin_id', $admin->id)
                ->update($updateData);

            // Todo:$messageを受け取るためのフラッシュメッセージ
            $message = $admin->name . 'さんの権限を' . $request->admin . 'に変更しました。';
            return redirect('admins/')
                ->with('admin_message', $message);
        }

        // 変更されてなかったらredirectして一覧に戻る。
        return redirect('admins/');
    }

}
