<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\State;
use App\Models\StateDistrict;
use App\Models\ParliamentalDistrict;
use DB;

class ElectionController extends Controller
{
    //
    public function electionProgressView(){
        // Check if any states are still undergoing voting 
        $stateProgress = StateDistrict::where('votingStatus','=',0)->get();
        if($stateProgress){
            $stateVotingStatus = 'Ongoing';
        }
        else{
            $stateVotingStatus = 'Done';
        }

        //Check if any parliment constituencies are still undergoing election
        $parlimentProgress = ParliamentalDistrict::where('votingStatus','=',0)->get()->first();
        if($parlimentProgress){
            $parlimentVotingStatus = 'Ongoing';
        }
        else{
            $parlimentVotingStatus = 'Done';
        }
        return view('viewelectionprogress')->with('state',$stateVotingStatus)->with('parliment',$parlimentVotingStatus);
    }

    public function parlimentProgressList(){
        // Get Ongoing Parliamental Election State Details
        $ongoingStateLists = DB::table('state')->join('parliamentaldistrict','state.stateId','=','parliamentaldistrict.stateId')->select('parliamentaldistrict.*','state.parliamentalDistrictCount')->where('votingStatus','=',0)->get();
        
        foreach($ongoingStateLists as $state){
            $totalVoterCount = ParliamentalDistrict::select('voterTotalCount')->where('stateId','=',$state->stateId)->sum('voterTotalCount');
            $currentVoteCount = ParliamentalDistrict::select('currentVoteCount')->where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalVoterCount;
            $state->currentVoteCount = $currentVoteCount;
        }
        
        // Get Done Voting Parliamental Election State Details
        $doneStateLists = DB::table('state')->join('parliamentaldistrict','state.stateId','=','parliamentaldistrict.stateId')->select('parliamentaldistrict.*','state.parliamentalDistrictCount')->where('votingStatus','=',1)->get();
        
        foreach($doneStateLists as $state){
            $totalVoterCount = ParliamentalDistrict::select('voterTotalCount')->where('stateId','=',$state->stateId)->sum('voterTotalCount');
            $currentVoteCount = ParliamentalDistrict::select('currentVoteCount')->where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalVoterCount;
            $state->currentVoteCount = $currentVoteCount;
        }

        return view('parliamentalelectionprogress')->with(compact('ongoingStateLists'))->with(compact('doneStateLists'));
    }
    
    public function stateProgressList(){
        $ongoingStateLists = StateDistrict::where('votingStatus','=',0)->get();
        $doneStateLists = StateDistrict::where('votingStatus','=',1)->get();
        return view('stateelectionprogress')->with(compact('ongoignStateLists'))->with(compact('doneStateLists'));
    }

    public function viewStateFederalElection(){
        return view('home');
    }

    public function parliamentalStateDetails(Request $request){
        $districts = ParliamentalDistrict::where('stateId','=', $request->ongoingstate)->get();

        return view('electionprogressdetails')->with(compact('districts'));
    }
}
