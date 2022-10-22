<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;    
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\DashboardController;
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

// Main View Route
Route::get('/federalElection', [VoteController::class, 'federalElection'])->name('federalElectionPage');
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/dashboardhome', function(){
    return view('dashboardhome');
})->name('dashboard');
Route::get('/stateElection', [VoteController::class, 'stateElection'])->name('stateElectionPage');
Route::get('/viewelectionprogress', [ElectionController::class, 'electionProgressView'])->name('viewelectionprogress');

// Dashboard Route
Route::get('/candidatedeposit', [DashboardController::class, 'candidatedepositView'])->name('candidatedepositpage');

// Vote Confirmation Page Route
Route::get('/voteConfirmation/{ic}', [VoteController::class, 'voteConfirmation'])->name('voteConfirmation');
Route::get('/voteConfirmation/{ic}/{electionType}', [VoteController::class, 'vote'])->name('castVote');

// Vote Progress Page Route
Route::get('/parliamentalelectionprogress', [ElectionController::class, 'parlimentProgressList'])->name('parlimentelectionprogress');
Route::get('/stateelectionprogress', [ElectionController::class, 'stateProgressList'])->name('stateelectionprogress');

// Federal Election Progress Page Route
Route::get('/parliamentalelectionprogress/{ongoingstate}', [ElectionController::class, 'parliamentalStateDetails'])->name('parliamentalelectionstate');

// Election Districts Details Page Route
Route::get('/electionprogressdetails/{districtid}', [ElectionController::class, 'electionProgressDetails'])->name('electionprogress');