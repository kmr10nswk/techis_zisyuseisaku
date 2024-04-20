<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'theme',
        'kind',
        'company',
        'release',
        'detail',
        'image_item',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    /**
     * リスト一覧
     */
    public static function category_list(){
        return [
            '1' => '対戦より',
            '2' => '協力より',
            '3' => 'どちらも',
            '4' => '不明',
        ];
    }

    public static function theme_list(){
        return [
            '1' => 'ファンタジー',
            '2' => '現代ファンタジー',
            '3' => 'ホラー',
            '4' => 'SF',
            '5' => '終末もの',
            '6' => '二次創作',
            '7' => 'その他',
        ];
    }
    
    public static function kind_list(){
        return [
            '1' => 'ルールブック',
            '2' => 'サプリメント',
            '3' => 'シナリオ集',
        ];
    }

    public static function company_list(){
        return [
            '1' => '冒険企画局',
            '2' => 'F.E.A.R.',
            '3' => 'SNE',
            '4' => 'その他',
        ];
    }
}
