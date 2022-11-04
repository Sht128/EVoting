<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\State;
use App\Models\StateDistrict;
use App\Models\ParliamentalDistrict;
use App\Models\Chart;
use DB;
use Stringable;

class ElectionController extends Controller
{
    /**
     * Returns Election Progress View
     *   
     */ 
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

    /**
     * Returns Election Results View
     */
    public function electionResultsView(){
        $parlimentDistrictCount = ParliamentalDistrict::get()->count();
        $parlimentalFinishCount = ParliamentalDistrict::where('votingStatus','=',1)->get()->count();
        $parliments = ParliamentalDistrict::join('candidate','candidate.ic','=','parliamentaldistrict.majorityCandidate')->where('votingStatus','=',1);
        if($parlimentDistrictCount == $parlimentalFinishCount){
            $parlimentVotingStatus = "Finish";
        }
        else{
            $parlimentVotingStatus = "Not Done";
        }
        $states = StateDistrict::join('candidate','candidate.ic','=','parliamentaldistrict.majorityCandidate')->where('votingStatus','=',1);

        return view('viewelectionresults')->with(compact('parliments'))->with(compact('states'));
    }

    /**
     * Returns Parliment Election Progress Data
     */
    public function parlimentProgressList(){
        // Get Ongoing Parliamental Election State Details
        $ongoingState = DB::table('state')->join('parliamentaldistrict','state.stateId','=','parliamentaldistrict.stateId')->select('parliamentaldistrict.*','state.parliamentalDistrictCount')->where('votingStatus','=',0)->get();
        $ongoingStateLists = $ongoingState->unique('stateId');

        // Find Voter and Vote Counts In Each Districts And Add It Into Collection
        foreach($ongoingStateLists as $state){
            $totalVoterCount = ParliamentalDistrict::select('voterTotalCount')->where('stateId','=',$state->stateId)->sum('voterTotalCount');
            $currentVoteCount = ParliamentalDistrict::select('currentVoteCount')->where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalVoterCount;
            $state->currentVoteCount = $currentVoteCount;
        }
        
        // Get Done Voting Parliamental Election State Details
        $doneState = DB::table('state')->join('parliamentaldistrict','state.stateId','=','parliamentaldistrict.stateId')->select('parliamentaldistrict.*','state.parliamentalDistrictCount')->where('votingStatus','=',1)->get();
        $doneStateLists = $doneState->unique('stateId');

        // Find Voter and Vote Counts In Each Districts And Add It Into Collection
        foreach($doneStateLists as $state){
            $totalVoterCount = ParliamentalDistrict::select('voterTotalCount')->where('stateId','=',$state->stateId)->sum('voterTotalCount');
            $currentVoteCount = ParliamentalDistrict::select('currentVoteCount')->where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalVoterCount;
            $state->currentVoteCount = $currentVoteCount;
        }

        return view('parliamentalelectionprogress')->with(compact('ongoingStateLists'))->with(compact('doneStateLists'));
    }
    
    public function stateProgressList(){
        // Get Ongoing State Election State Details
        $ongoingState = State::join('statedistrict','state.stateId','=','statedistrict.stateId')->select('statedistrict.*','state.stateDistrictCount')->where('votingStatus','=',0)->get();
        $ongoingStateLists = $ongoingState->unique('stateId');
        foreach($ongoingStateLists as $state){
            $totalVoterCount = StateDistrict::select('voterTotalCount')->where('stateId','=',$state->stateId)->sum('voterTotalCount');
            $currentVoteCount = StateDistrict::select('currentVoteCount')->where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalVoterCount;
            $state->currentVoteCount = $currentVoteCount;
        }

        // Get Finished Voting Election State Details
        $doneState = State::join('statedistrict','state.stateId','=','statedistrict.stateId')->select('statedistrict.*','state.stateDistrictCount')->where('votingStatus','=',1)->get();
        $doneStateLists = $doneState->unique('stateId');
        foreach($doneStateLists as $state){
            $totalVoterCount = StateDistrict::select('voterTotalCount')->where('stateId','=',$state->stateId)->sum('voterTotalCount');
            $currentVoteCount = StateDistrict::select('currentVoteCount')->where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalVoterCount;
            $state->currentVoteCount = $currentVoteCount;
        }
        return view('stateelectionprogress')->with(compact('ongoingStateLists'))->with(compact('doneStateLists'));
    }

    public function parliamentalStateDetails(Request $request){
        $districts = ParliamentalDistrict::where('stateId','=', $request->ongoingstate)->get();
        $electionType = 'Federal Election';
        return view('electionprogressdetails')->with(compact('districts'))->with('electiontype', $electionType);
    }

    public function stateElectionStateDetails(Request $request){
        $districts = StateDistrict::where('stateId','=',$request->ongoingstate)->get();
        $electionType = 'State Election';
        return view('electionProgressDetails')->with(compact('districts'))->with('electiontype',$electionType);
    }

    public function electionProgressDetails(Request $request){
        if ($request->election == 'Federal Election'){
            $candidates = (array) Candidate::select('name','parliamentalVoteCount')->where('parliamentalConstituency','=',$request->districtid)->pluck('parliamentalVoteCount','name')->all();
            $district = ParliamentalDistrict::where('districtId','=',$request->districtId)->first();
            $chart = new Chart;
            $chart->labels = (array_keys($candidates));
            $chart->dataset = (array_values($candidates));

            for ($i=0; $i<=count($candidates); $i++) {
                $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            }
            $chart->colors = $colours;

            return view('electionprogress')->with(compact('chart'))->with(compact('district'));
        }
        elseif($request->election == 'State Election'){
            $candidates = Candidate::select('name','stateVoteCount')->where('stateConstituency','=',$request->districtid)->pluck('stateVoteCount','name')->all();
            $district = StateDistrict::where('districtId','=',$request->districtId)->get();
            $chart = new Chart;
            $chart->labels = (array_keys($candidates));
            $chart->dataset = (array_values($candidates));

            for ($i=0; $i<=count($candidates); $i++) {
                $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
            }
            $chart->colors = $colours;

            return view('electionprogress')->with(compact('chart'))->with(compact('district'));
        }
    }
}
