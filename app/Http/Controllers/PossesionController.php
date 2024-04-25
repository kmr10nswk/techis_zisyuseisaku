<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Possesion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PossesionController extends Controller
{
    /**
     * 商品所持
     */
    public function add(Request $request)
    {
        \Log::info(!Auth::guard('admin')->check());
        $user = !Auth::guard('admin')->check();
        $itemId = $request->possesion_id;

        if(!$user->is_possesion($itemId)) {
            $user->possesion_items()->attach($itemId);
            $response = Possesion::orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->first();

            return $response;
        }
        return false;
    }
    
    public function remove(Request $request)
    {
        $user = !Auth::guard('admin')->check();
        $itemId = $request->possesion_id;

        if($user->is_possesion($itemId)){
            $user->possesion_items()->detach($itemId);

            return true;
        }
        return false;
    }

}
