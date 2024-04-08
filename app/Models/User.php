<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Policy;

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
        'sex',
        'age',
        'email',
        'password',
        'image_icon',
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
    public function policies()
    {
        return $this->hasMany('App\Models\Policy');
    }

    /**
     * 権限判断コード
     */
    public static function admin($user){
        if($user->item_admin === 1 && $user->theread_admin === 1){
            $user['admin'] = '全て';
        } elseif($user->item_admin === 1){
            $user['admin'] = '商品';
        } elseif($user->theread_admin === 1){
            $user['admin'] = '掲示板';
        } else {
            $user['admin'] = '一般';
        }

        return $user['admin'];
    }
}
