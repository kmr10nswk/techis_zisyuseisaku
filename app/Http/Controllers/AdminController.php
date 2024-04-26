<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CurrentPasswordRule;

class AdminController extends Controller
{
    /**
     * Admin一覧
     */
    public function index(Request $request){
        $admins = Admin::query();
        $admin_list = ['商品', '掲示板', '全て'];

        // 検索用
        $search = $request->only(['search_name', 'search_email', 'search_admin']);
        $this->validate($request, [
            'search_name' => ['string', 'max:30', 'nullable'],
            'search_email' => ['string', 'max:50', 'nullable'],
            'search_admin' => ['in:' . implode(',', $admin_list), 'nullable'],
        ]);

        $admins = $this->search($admins, $search);
        $nothing_message = null;
        if($admins->first() === null) {
            $nothing_message =  '検索結果はありません';
        }

        // 通常
        $admins = $admins->where('deleted_at', null)
            ->orderby('id', 'asc')
            ->paginate(10)->withQueryString();
        
        foreach($admins as $admin){
            $admin['policy_name'] = Admin::policyType($admin);
        }

        return view('admin.index', compact('admins', 'admin_list', 'search'));
    }

    /**
     * Adminユーザー編集画面
     */
    public function profile_edit(){
        $admin = Auth::guard('admin')->user();
        return view('admin.profile_edit', compact('admin'));
    }

    /**
     * Adminユーザー編集動作
     */
    public function profile_update(Request $request, Admin $admin){
            $this->Validate($request, [            
                'name' => ['required', 'string' , 'max:20'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . Auth::guard('admin')->user()->email . ',email'],
                'now_password' => ['required', 'min:6' , 'max:20' , new CurrentPasswordRule(), 'nullable'],
                'password' => ['confirmed', 'min:6', 'max:20' , 'nullable'],
            ]);
    
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];
                
            // パスワードが入力されていれば、更新データに追加
            if($request->password) {
                $data['password'] = Hash::make($request->password);
            }
            
            // 更新
            Admin::where('id', $admin->id)
                ->update($data);
    
            return redirect('admins/');
    }    
    
    /**
     * Admin権限編集画面
     */
    public function edit($id){
        $admin = Admin::find($id);
        $admin['policy_name'] = Admin::policyType($admin);
        return view('admin.edit', compact('admin'));
    }

    /**
     * Admin権限編集動作
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

    /**
     * アカウント削除
     */
    public function delete($id){
        Admin::where('id', $id)
            ->update([
                'deleted_at' => now(),
            ]);

        return redirect()->route('admins.index');
    }

    /**
     * 検索用
     */
    public function search($query, $search)
    {
        if(isset($search['search_name'])){
            $query = $query->where('name', 'like', '%' . $search['search_name'] . '%');
        }
        
        if(isset($search['search_email'])){
            $query = $query->where('email', 'like', '%' . $search['search_email'] . '%');
        }
        
        // if(isset($search['search_admin'])){
        //     // Todo:わからん
        //     $query = $query->where();
        // }
        
        return $query;
    }
}
