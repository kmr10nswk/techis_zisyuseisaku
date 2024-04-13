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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

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

// TODO:adminregisterは管理者のみ入れる
Route::get('/login/admin', [LoginController::class, 'showAdminLoginForm']);
Route::post('/login/admin', [LoginController::class, 'adminLogin']);
Route::get('/register/admin', [RegisterController::class, 'showAdminRegisterForm']);
Route::post('/register/admin', [RegisterController::class, 'registerAdmin']);

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Item
Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/add', [ItemController::class, 'add']);
    Route::post('/add', [ItemController::class, 'add']);
});

// User
Route::prefix('users')->group(function (){
    Route::get('/', [UserController::class, 'index']);
    Route::patch('/delete/{id}',[UserController::class, 'delete']);
    
    Route::get('/profile/edit/{user}',[UserCOntroller::class, 'profile_edit']);
    Route::get('/profile/update/{user}',[UserCOntroller::class, 'profile_update']);
});

// Admin
Route::prefix('admins')->group(function (){
    Route::get('/', [AdminController::class, 'admin_index']);
    Route::patch('/delete/{id}',[AdminController::class, 'admin_delete']);

    Route::get('/edit/{id}',[AdminController::class, 'admin_edit']);
    Route::patch('/update/{user}',[AdminController::class, 'admin_update']);
});
