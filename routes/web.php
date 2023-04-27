<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoatController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/login/custom', [App\Http\Controllers\Auth\LoginController::class, 'login_custom'])->name('login.custom');

Route::post('/register/custom', [App\Http\Controllers\Auth\RegisterController::class, 'register_custom'])->name('register.custom');
Route::get('/register/confirmation-code', [App\Http\Controllers\Auth\RegisterController::class, 'confirmasi'])->name('register.confirmation');
Route::post('/register/confirmation-process', [App\Http\Controllers\Auth\RegisterController::class, 'confirmasi_process'])->name('register.confirmation-process');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('boats', BoatController::class);

    Route::post('/users/confirmation', [UserController::class, 'confirmation'])->name('users.confirmation-asu');
});
