<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VoteController;    
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
    return view('auth.login');
});
 
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{ic}', function (EmailVerificationRequest $request) {
    $request->fulfill();
 
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
 
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send'); 

Auth::routes(['verify'=>true]);

// Main View Route
Route::get('/federalElection', [VoteController::class, 'federalElection'])->name('federalElectionPage');
Route::get('/home', [HomeController::class, 'home'])->middleware(['auth','verified'])->name('home');
Route::get('/dashboardhome', function(){
    return view('dashboardhome');
})->name('dashboard');
Route::get('/stateElection', [VoteController::class, 'stateElection'])->name('stateElectionPage');
Route::get('/viewelectionprogress', [ElectionController::class, 'electionProgressView'])->name('viewelectionprogress');
Route::get('/voterprofile', [HomeController::class, 'voterProfile'])->name('voterprofile');
Route::get('/viewelectionresults', [ElectionController::class, 'electionResultsView'])->name('viewelectionresults');

// Dashboard Route
Route::get('/candidatedeposit', [DashboardController::class, 'candidatedepositView'])->name('candidatedepositpage');
Route::get('/raceanalytics', [DashboardController::class, 'raceAnalyticsView'])->name('raceanalytic');
Route::get('/allelectionanalytics',[DashboardController::class, 'allElectionsView'])->name('allelections');
Route::get('/candidatepartyanalytics',[DashboardController::class, 'candidatePartiesView'])->name('candidateparty');
// Vote Confirmation Page Route
Route::get('/voteConfirmation/{ic}', [VoteController::class, 'voteConfirmation'])->name('voteConfirmation');
Route::get('/verifyvote/{electionType}', [VoteController::class,'vote'])->name('castVote');
Route::get('/verifyvote', [VerificationController::class, 'verifyVoteView'])->name('verifyVote');
Route::post('/verifyvote', [VerificationController::class, 'verifyCode'])->name('authenticateCode');
Route::post('/verifyvote/resend',[VerificationController::class, 'resendEmail'])->name('resendCode');

// Vote Progress Page Route
Route::get('/parliamentalelectionprogress', [ElectionController::class, 'parlimentProgressList'])->name('parlimentelectionprogress');
Route::get('/stateelectionprogress', [ElectionController::class, 'stateProgressList'])->name('stateelectionprogress');

// Federal Election Progress Page Route
Route::get('/parliamentalelectionprogress/{ongoingstate}', [ElectionController::class, 'parliamentalStateDetails'])->name('parliamentalelectionstate');
Route::get('/stateelectionprogress/{ongoingstate}', [ElectionController::class, 'stateElectionStateDetails'])->name('stateelectionstate');

// Election Districts Details Page Route
Route::get('/electionprogressdetails/{districtid}', [ElectionController::class, 'electionProgressDetails'])->name('electionprogress');

// Election Results Page Route
Route::get('/viewelectionparties/{stateId}', [ElectionController::class, 'electionStatePartiesResult'])->name('electionpartiesresult');
Route::get('/viewelectiondistricts/{districtId}/{electionType}', [ElectionController::class, 'electionDistrictsResult'])->name('electiondistrictsresult');

// Voter Analytics Page Route
Route::get('/allvoteranalytics', [DashboardController::class, 'allVoterAnalyticsView'])->name('allVoterAnalytics');
Route::get('/allvoterrace', [DashboardController::class, 'allVoterRaceView'])->name('allVoterRace');
Route::get('/parliamentdistrictrace', [DashboardController::class, 'parliamentVoterRaceView'])->name('parliamentVoterRace');
Route::get('/statedistrictrace', [DashboardController::class, 'stateVoterRaceView'])->name('stateVoterRace');
Route::get('votedvoterrace', [DashboardController::class, 'votedVoterRaceView'])->name('votedVoterRace');

// Candidate Deposit Page Route
Route::post('/candidatedeposit', [DashboardController::class, 'candidateDepositFilter'])->name('candidatedepositfilter');