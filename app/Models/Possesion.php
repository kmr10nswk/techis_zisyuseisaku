<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\User;
use app\Models\Item;

class Possesion extends Model
{
    use HasFactory;

    /**
     * リレーション
     */
    public function users(){
        return $this->belongsTo('app\Models\User');
    }

    public function items(){
        return $this->belongsTo('app\Modes\Item');
    }
}
