<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Rules\CurrentPasswordRule;
use Google\Cloud\Storage\StorageClient;

class UserController extends Controller
{
    /**
     * User一覧
     */
    public function index(Request $request){
        $users = User::query()->with('possesions');
        $types['gmpl'] = ['GMのみ', 'PLのみ', 'GMより', 'PLより'];
        $types['style'] = ['ボイスのみ', 'テキストのみ', '半分テキスト'];
        $types['item'] = Item::pluck('name', 'id');
        
        // 検索用
        $search = $request->only(['search_free', 'search_name', 'search_gmpl', 'search_style', 'search_item']);

        $this->validate($request, [
            'search_free' => ['string', 'nullable'],
            'search_name' => ['string', 'nullable'],
            'search_gmpl' => ['in:' . implode(',', $types['gmpl']), 'nullable'],
            'search_style' => ['in:' . implode(',', $types['style']), 'nullable'],
            'search_item' => ['string', 'max:100', 'nullable'],
        ]);

        $users = $this->search($users, $search);
        $nothing_message = null;
        if($users->first() === null) {
            $nothing_message =  '検索結果はありません';
        }

        // 通常
        $users = $users->where('status', 'active')
            ->orderby('id', 'desc')
            ->paginate(20)->withQueryString();
        
        $users = User::noEmail($users);

        // 画像の取得
        $client = new StorageClient();
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);
        // signedUrlで簡単に署名付きURLが取得できる
        foreach($users as $user){
            $user->url = $bucket->object($user->image_item)->signedUrl(
                new \Datetime('tomorrow'),
            );
        }

        return view('user.index', compact('users', 'types', 'search', 'nothing_message'));
    }

    /**
     * プロフィール画面表示
     */
    public function profile_show($id)
    {
        $users_obj = User::where('id', $id)->with('possesions');
        
        // Email渡さないように
        $users = $users_obj->get();
        $users = User::noEmail($users);
        $user = $users->first();

        // 所持ルールブック一覧
        $user['possesions'] = $user->possesion_items()
            ->orderBy('name','asc')
            ->get();
        if(auth()->check()){
            $checkUser = Auth::user()->id;
        } else {
            $checkUser = "";
        }

        //画像読み込み
        $client = new StorageClient();
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);
        // signedUrlで簡単に署名付きURLが取得できる
        $user->url = $bucket->object($user->image_item)->signedUrl(
            new \Datetime('tomorrow'),
        );

        return view('user.profile', compact('user', 'checkUser'));
    }

    /**
     * プロフィール編集 画面表示
     */
    public function profile_edit()
    {
        $user = Auth::user();
        $user->session_style =  explode(',' ,$user->session_style);
        
        //画像読み込み
        $client = new StorageClient();
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);
        // signedUrlで簡単に署名付きURLが取得できる
        $user->url = $bucket->object($user->image_item)->signedUrl(
            new \Datetime('tomorrow'),
        );

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
            'oneword' => ['string', 'max:20', 'nullable'],
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
        $data['image_icon'] = isset($file)
            ? User::uploadImage($file, 'icon')
            : $user->image_icon;

        // 更新
        User::where('id', $user->id)
            ->update($data);

        return redirect()->route('users.profile.edit');
    }

    /**
     * アカウント削除
     */
    public function delete($id){
        User::where('id', $id)
            ->update([
                'status' => 'deleted',
            ]);

        return redirect()->route('users.index');
    }

    /**
     * 検索用
     */
    public function search($query, $search){
        if(isset($search['search_free'])){
            $query = $query->where('nickname', 'like', '%' . $search['search_free'] . '%')
                ->orWhere('name', 'like', '%' . $search['search_free'] . '%')
                ->orWhere('oneword', 'like', '%' . $search['search_free'] . '%')
                ->orWhere('comment', 'like', '%' . $search['search_free'] . '%')
                ->orWhere('email', 'like', '%' . $search['search_free'] . '%');
        }

        if(isset($search['search_name'])){
            $query = $query->where('nickname', 'like', '%' . $search['search_name'] . '%');
        }

        if(isset($search['search_gmpl'])){
            $query = $query->where('gmpl', $search['search_gmpl']);
        }

        if(isset($search['search_style'])){
            $query = $query->where('session_style', 'like', '%' . $search['search_style'] . '%');
        }

        if(isset($search['search_item'])){
            $itemId = $search['search_item'];
            $query = $query->whereIn('id', function ($q) use ($itemId) {
                $q->select('user_id')
                    ->from('possesions')
                    ->where('item_id', $itemId);
            });
        }

        return $query;
    }

}
