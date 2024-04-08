<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * User一覧
     */
    public function index(Request $request){

        $users = User::with('policies')
            ->orderby('id', 'asc')
            ->paginate(10);
        
        return view('user.index', compact('users', ));
    }
}
