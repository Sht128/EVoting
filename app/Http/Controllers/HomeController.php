<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\Vote;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * 
     * Loads Home Page
     */
    public function home(){
        $userVoteCount = 0;
        if(Auth::check()){
            if(Auth::user()->parlimentVoteStatus == 0){
                $userVoteCount = $userVoteCount + 1;
            }
            if(Auth::user()->stateVoteStatus == 0){
                $userVoteCount = $userVoteCount + 1;
            }
        }
        Session::put('userVoteCount', $userVoteCount);
        return view('home');
     }

     /**
      * Finds Voter History
      * @return $response 
      */
      public function voterProfile(){
        if(Auth::check()){
            if(Auth::user()->parlimentVoteStatus == 1){
                $vote = Vote::where(['seatingId' => Auth::user()->parliamentalConstituency, 'electionType' => 'Federal Election'])->first();
                $candidate = decrypt($vote->candidateId);

                $federalCandidate = Candidate::select('name','party')->where('ic','=',$candidate)->first();
            }

            if(Auth::user()->stateVoteStatus == 1){
                $vote = Vote::where(['seatingId' => Auth::user()->stateConstituency, 'electionType' => 'State Election'])->first();
                $candidate = decrypt($vote->candidateId);

                $stateCandidate = Candidate::select('name','party')->where('ic','=',$candidate)->first();
            }

            return view('voterprofile')->with(compact('federalCandidate'))->with(compact('stateCandidate'));
        }
      }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
