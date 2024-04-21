<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;

class Possesion extends Model
{
    use HasFactory;

    /**
     * リレーション
     */
    public function users(){
        return $this->belongsTo('App\Models\User');
    }

    public function items(){
        return $this->belongsTo('App\Models\Item');
    }
}
