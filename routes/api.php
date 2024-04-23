<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Possesion;
use App\Http\Controllers\PossesionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Possesion
Route::group(['middleware' => ['web']], function () {
    Route::prefix('possesions')->group(function (){
        Route::post('/add', [PossesionController::class, 'add']);
        Route::delete('/remove', [PossesionController::class, 'remove']);
    });
});

