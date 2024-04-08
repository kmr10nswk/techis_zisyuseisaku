<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Item;
use App\Models\Policy;
use App\Models\Possesion;

use App\Http\Controllers\Homecontroller;
use App\Http\Controllers\Itemcontroller;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/add', [ItemController::class, 'add']);
    Route::post('/add', [ItemController::class, 'add']);
});

Route::prefix('users')->group(function (){
    Route::get('/', [UserController::class, 'index']);
    Route::get('/admin_edit/{id}',[UserController::class, 'admin_edit']);
    Route::patch('/admin_update/{id}',[UserController::class, 'admin_update']);
    Route::patch('/delete/{id}',[UserController::class, 'delete']);
});
