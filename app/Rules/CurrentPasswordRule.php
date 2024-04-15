<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CurrentPasswordRule implements ValidationRule
{
    /**
     * 現在のパスワード確認
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // ログインチェックとパスワード抜き出し
        if (Auth::check()) {
            $nowPassword = Auth::user()->password;
        } elseif(Auth::guard('admin')->check()) {
            $nowPassword = Auth::guard('admin')->user()->password;
        }

        if(!Hash::check($value, $nowPassword)){
            $fail('現在のパスワードが正しくありません。');
        }
    }
}
