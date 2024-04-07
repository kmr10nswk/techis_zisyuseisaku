<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Policy extends Model
{
    use HasFactory;
    /**
     * リレーション
     */
    public function users()
    {
        return $this->belong('App\Models\User');
    }
}
