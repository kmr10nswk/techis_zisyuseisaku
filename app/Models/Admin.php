<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'password' => 'hashed',
    ];


    /**
     * リレーション
     */
    public function policy()
    {
        return $this->hasOne('App\Models\Policy');
    }

    /**
     * 権限判断コード
     */
    public static function admin($admin){
        if($admin->policy->item_admin === 1 && $admin->policy->theread_admin === 1){
            $admin['admin'] = '全て';
        } elseif($admin->policy->item_admin === 1){
            $admin['admin'] = '商品';
        } elseif($admin->policy->theread_admin === 1){
            $admin['admin'] = '掲示板';
        } else {
            $admin['admin'] = '一般';
        }

        return $admin['admin'];
    }
}