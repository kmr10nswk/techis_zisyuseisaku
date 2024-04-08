<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Policy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ippan_admin',
        'item_admin',
        'theread_admin',
    ];

    /**
     * リレーション
     */
    public function users()
    {
        return $this->belong('App\Models\User');
    }
}
