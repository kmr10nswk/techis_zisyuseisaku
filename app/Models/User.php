<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Str;

use App\Models\Policy;
use App\Models\Possesion;
use App\Models\Item;
use Stringable;

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

    public function possesion_items(){
        return $this->belongsToMany('App\Models\Item', 'possesions', 'user_id', 'item_id')
            ->withTimestamps();
    }

    public function is_possesion($itemId){
        return $this->possesions()->where('item_id', $itemId)->exists();
    }

    /**
     * 一般ユーザーの場合はemail情報を渡さない
     */
    public static function noEmail($users) {
        if(!Auth::guard('admin')->check()){
            foreach($users as $user){
                $user = $user->makeHidden('email');
            }
        }
        return $users;
    }

    /**
     * 画像アップロード
     * @param ファイル情報, 保存先
     * @return string || bool
     */
    public static function uploadImage($file, $path) {
        // バケットの指定
        $client = new StorageClient();

        // ローカルと本番で保存バケットを変更
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);

        // ランダム文字列の生成
        $uuid = Str::uuid()->toString();
        // パス/ランダム文字列.拡張子
        $file_path = $path . '/' .$uuid . '.' . $file->getClientOriginalExtension();

        //UPLOAD FILE TO GCS
        // 'r'はread 書き込み自体は'name' => $file_pathの部分でやってる。
        $object = $bucket->upload(fopen($file->getRealPath(), 'r'), 
        [
            'name'=> $file_path,
        ]);

        return $file_path;
    }
}
