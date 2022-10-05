<?php

use Http\Controllers\Auth\RegisterController;
use Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;    
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


Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/federalElection', [VoteController::class, 'federalElection'])->name('federalElectionPage');
Route::get('/stateElection', [VoteController::class, 'stateElection'])->name('stateElectionPage');
