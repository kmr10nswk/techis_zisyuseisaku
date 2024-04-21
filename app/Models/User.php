<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

use App\Models\Policy;
use App\Models\Possesion;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'name',
        'gmpl',
        'session_style',
        'email',
        'password',
        'image_icon',
        'oneword',
        'comment',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * リレーション
     */
    public function possesions(){
        return $this->hasMany('App\Models\Possesion');
    }


    /**
     * 一般ユーザーの場合はemail情報を渡さない
     */
    public static function noEmail($users) {
        if(Auth::user() && $users->count() > 1){
            foreach($users as $user){
                $user = $user->makeHidden('email');
            }
        } elseif(Auth::user()) {
            $users->makeHidden('email');
        }
        return $users;
    }

    /**
     * 画像アップロード
     * @param ファイル情報, 保存先
     * @return string || bool
     */
    public static function uploadImage($file, $path) {
        // image_iconの時
        if(isset($file) && $path === 'icon'){
            $name = $file->store('public/' . $path);
            return (basename($name));
        } elseif(!isset($file) && $path === 'icon') {
            return 'default_icon_1.png';
        }
        
        // image_itemの時
        if(isset($file) && $path === 'item') {
            $name = $file->store('public/' . $path);
            return (basename($name));
        } elseif(!isset($file) && $path === 'item') {
            return 'default_item_1.jpg';
        }
    }
}
