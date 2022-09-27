<?php

use Http\Controllers\Auth\RegisterController;
use Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.register');
});

Auth::routes();

Route::post('/auth.register',[RegisterController::class, 'register'])->name('registration');

Route::get('/login', [LoginController::class, 'view'])->name('login');
Route::get('/home', [HomeController::class, 'index'])->name('home');
