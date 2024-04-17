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
use App\Http\Controllers\AdminController;
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
// addは元からあったやつ。getとpostで使い分けてて頭がいいが、俺はpatchの方が好き。
Route::prefix('items')->name('items.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
    Route::get('/add', [ItemController::class, 'add'])->name('add');
    Route::post('/add', [ItemController::class, 'add'])->name('add.post');
});

// User
Route::prefix('users')->name('users.')->group(function (){
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::patch('/delete/{id}',[UserController::class, 'delete'])->name('delete');
    
    Route::get('/profile/show/{user}',[UserController::class, 'profile_show'])->name('profile.show');
    Route::get('/profile/edit',[UserController::class, 'profile_edit'])->name('profile.edit');
    Route::patch('/profile/update/{user}',[UserController::class, 'profile_update'])->name('profile.update');
});

// Admin
Route::prefix('admins')->name('admins.')->group(function (){
    Route::get('/', [AdminController::class, 'index']);
    Route::patch('/delete/{id}',[AdminController::class, 'delete']);

    Route::get('/edit/{id}',[AdminController::class, 'edit']);
    Route::patch('/update/{admin}',[AdminController::class, 'update']);
    Route::get('profile/edit',[AdminController::class, 'profile_edit'])->name('profile.edit');
    Route::patch('profile/update/{admin}',[AdminController::class, 'profile_update'])->name('profile.update');
});
