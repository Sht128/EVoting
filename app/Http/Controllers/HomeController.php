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
                $votes = Vote::where(['seatingId' => Auth::user()->parliamentalConstituency, 'electionType' => 'Federal Election'])->get();
                $candidate = '';
                foreach($votes as $vote){
                    $vote->voterId = decrypt($vote->voterId);
                    if($vote->voterId == Auth::user()->ic){
                        $candidate = decrypt($vote->candidateId);
                    }
                }
                $federalCandidate = Candidate::select('name','party')->where('ic','=',$candidate)->first();
            }
            else{
                $federalCandidate = null;
            }

            if(Auth::user()->stateVoteStatus == 1){
                $votes = Vote::where(['seatingId' => Auth::user()->stateConstituency, 'electionType' => 'State Election'])->get();
                $candidate = '';
                foreach($votes as $vote){
                    $vote->voterId = decrypt($vote->voterId);
                    if($vote->voterId == Auth::user()->ic){
                        $candidate = decrypt($vote->candidateId);
                    }
                }
                

                $stateCandidate = Candidate::select('name','party')->where('ic','=',$candidate)->first();
            }
            else{
                $stateCandidate = null;
            }

            return view('voterprofile')->with(compact('federalCandidate'))->with(compact('stateCandidate'));
        }
      }
}
