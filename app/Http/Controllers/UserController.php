<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Policy;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * User一覧
     */
    public function index(Request $request){
        $users = User::query();
        
        $users = $users->orderby('id', 'asc')
            ->paginate(10)->withQueryString();
        
        $users = User::noEmail($users);

        return view('user.index', compact('users'));
    }

    /**
     * プロフィール画面表示
     */
    public function profile_edit(User $user)
    {
        User::noEmail($user);
        return view('user.profile', compact('user'));
    }

    /**
     * プロフィール更新動作
     */
    public function profile_update(Request $request, User $user){



    }

    /**
     * アカウント削除
     */
    public function delete(Request $request, User $user){
        return ('まだ実装してない');
    }

}
