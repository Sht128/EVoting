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

class DashboardController extends Controller
{
    

    public function raceAnalyticsView(){
        return view('raceanalytics');
    }

    public function allElectionsView(){
        $parliamentals = ParliamentalDistrict::orderBy('stateId')->get();
        foreach($parliamentals as $parlimental){
            $voteCountLeft = $parlimental->voterTotalCount - $parlimental->currentVoteCount;
            $parlimental->remainingVote = $voteCountLeft;
        }

        $states = StateDistrict::orderBy('stateId')->get();
        foreach($states as $state){
            $voteCountLeft = $state->voterTotalCount - $state->currentVoteCount;
            $state->remainingVote = $voteCountLeft;
        }

        return view('allelectionanalytics')->with(compact('parliamentals'))->with(compact('states'));
    }
    
    public function electionProgressDetails(Request $request){
        if ($request->electionType == 'Federal Election'){
            $candidates = (array) Candidate::select('name','parliamentalVoteCount')->where('parliamentalConstituency','=',$request->districtId)->pluck('parliamentalVoteCount','name')->all();
            $district = ParliamentalDistrict::where('districtId','=',$request->districtId)->first();
            $chart = new Chart;
            $chart->labels = (array_keys($candidates));
            $chart->dataset = (array_values($candidates));

            return view('electionprogressdashboard')->with(compact('chart'));
        }
        elseif($request->electionType == 'State Election'){
            $candidates = Candidate::select('name','stateVoteCount')->where('stateConstituency','=',$request->districtId)->pluck('stateVoteCount','name')->all();
            $chart = new Chart;
            $chart->labels = (array_keys($candidates));
            $chart->dataset = (array_values($candidates));

            return view('electionprogressdashboard')->with(compact('chart'));
        }
    }

    public function electionResultsDashboard(){
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
        return view('electionresultdashboard')->with('state',$stateVotingStatus)->with('parliment',$parlimentVotingStatus);
    }

    public function electionStateResults(){
        $states = State::where('stateVotingStatus','=',1)->get();
        foreach($states as $state){
            $totalDistrict = StateDistrict::where('stateId','=',$state->stateId)->sum('currentVoteCount');
            $state->totalVoterCount = $totalDistrict;
        }

        return view('stateresultsdashboard')->with(compact('states'));
    }

    public function electionPartiesResult(Request $request){
        $parties =(array) StateDistrict::join('candidate','candidate.ic','=','statedistrict.majorityCandidate')->groupBy('candidate.party')->select('candidate.party',DB::raw('count(*) as total'))->where('statedistrict.stateId','=',$request->stateId)->pluck('total','candidate.party as party')->all();

        $chart = new Chart();
        $chart->labels = (array_keys($parties));
        $chart->dataset = (array_values($parties));

        return view('electionpartyresultdashboard')->with(compact('chart'));

    }

    public function electionDistrictResult(){
        $parliamentals = ParliamentalDistrict::orderBy('stateId')->join('candidate','candidate.ic','=','parliamentaldistrict.majorityCandidate')->select('candidate.name','parliamentaldistrict.*')->where('votingStatus','=',1)->get();
        $states = StateDistrict::orderBy('stateId')->join('candidate','candidate.ic','=','statedistrict.majorityCandidate')->select('candidate.name','statedistrict.*')->where('votingStatus','=',1)->get();

        return view('districtresultdashboard')->with(compact('parliamentals'))->with(compact('states'));

    }

    public function parliamentalElectionSummary(){
        $parlimentDistrictCount = ParliamentalDistrict::get()->count();
        $parlimentalFinishCount = ParliamentalDistrict::where('votingStatus','=',1)->get()->count();
        $parliments = ParliamentalDistrict::join('candidate','candidate.ic','=','parliamentaldistrict.majorityCandidate')->select('candidate.party','parliamentaldistrict.*')->where('votingStatus','=',1)->get();
        $federalparties = ParliamentalDistrict::join('candidate','candidate.ic','=','parliamentaldistrict.majorityCandidate')->groupBy('candidate.party')->select('candidate.party',DB::raw('count(*) as total'))->where('votingStatus','=',1)->get();
        if($parlimentDistrictCount == $parlimentalFinishCount){
            $parlimentVotingStatus = "Finish";
        }
        else{
            $parlimentVotingStatus = "Not Done";
        }
        $data =(array) ParliamentalDistrict::join('candidate','candidate.ic','=','parliamentaldistrict.majorityCandidate')->groupBy('candidate.party')->select('candidate.party',DB::raw('count(*) as total'))->where('votingStatus','=',1)->pluck('total','candidate.party')->all();
        $chart = new Chart;
        $chart->labels = array_keys($data);
        $chart->dataset = array_values($data);

        return view('parliamentalelectionsummary')->with(compact('federalparties'))->with(compact('chart'));
    }   

    public function allVoterAnalyticsView(){
        $parlimentVoted = Voter::where(['parlimentVoteStatus'=>1,'stateVoteStatus'=>0])->get()->count();
        $parlimentNotVoted = Voter::where('parlimentVoteStatus','=',0)->get()->count();
        $notVoted = Voter::where(['parlimentVoteStatus'=>0,'stateVoteStatus'=>0])->get()->count();
        $alrVoted = Voter::where(['parlimentVoteStatus'=>1,'stateVoteStatus'=>1])->get()->count();
        $stateVoted = Voter::where(['parlimentVoteStatus'=>0,'stateVoteStatus'=>1])->get()->count();
        $stateNotVoted = Voter::where('stateVoteStatus','=',0)->get()->count();

        $data = ['Only Federal Election Voted'=>$parlimentVoted,'Not Voted'=>$notVoted,'Already Voted'=>$alrVoted,'Only State Election Voted'=>$stateVoted];
        $chart = new Chart;
        $chart->labels = (array_keys($data));
        $chart->dataset = (array_values($data));

        return view('allvoteranalytics')->with(compact('chart'));
    }

    public function stateList(){
        $states = State::orderBy('stateId')->get();

        return view('statelist')->with(compact('states'));
    }

    public function districtList(){
        $parliamentals = ParliamentalDistrict::orderBy('stateId')->get();
        $states = StateDistrict::orderBy('stateId')->get();

        return view('districtslist')->with(compact('parliamentals'))->with(compact('states'));
    }

    public function allVoterRaceView(){
        $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->pluck('total','race')->all();

        $chart = new Chart;
        $chart->labels = (array_keys($voters));
        $chart->dataset = (array_values($voters));

        return view('allvoterrace')->with(compact('chart'));
    }

    public function parliamentVoterRaceView(Request $request){
        $electionType = $request->electionType;
        if($electionType == 'Federal Election'){
            $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->where('parliamentalConstituency','=',$request->districtId)->pluck('total','race')->all();
        }
        elseif($electionType == 'State Election'){
            $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->where('stateConstituency','=',$request->districtId)->pluck('total','race')->all();

        }
        $chart = new Chart;
        $chart->labels = (array_keys($voters));
        $chart->dataset = (array_values($voters));

        return view('districtvoterrace')->with(compact('chart'));
    }

    public function stateVoterRaceView(Request $request){
        $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->where('state','=',$request->stateId)->pluck('total','race')->all();

        $chart = new Chart;
        $chart->labels = (array_keys($voters));
        $chart->dataset = (array_values($voters));

        return view('statevoterrace')->with(compact('chart'));
    }

    public function votedVoterRaceView(Request $request){
        $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->where(['parlimentVoteStatus' => 1, 'stateVoteStatus' => 1])->pluck('total','race')->all();

        $chart = new Chart;
        $chart->labels = (array_keys($voters));
        $chart->dataset = (array_values($voters));

        return view('votedvoterrace')->with(compact('chart'));
    }

    public function candidateListView(){
        $candidates = Candidate::orderBy('registeredState')->get();

        return view('allcandidatelist')->with(compact('candidates'));
    }
    
    public function candidatedepositView(){
        $candidateList = Candidate::orderBy('name')->get();

        return view('candidatedeposit')->with(compact('candidateList'));
    }

    public function candidateDepositFilter(Request $request){
        $selection = $request->depositfilter;

        if($selection == 'done'){
            $candidateList = Candidate::join('state','state.stateId','=','registeredState')->where('state.stateVotingStatus','=',1)->get();
        }
        else if($selection == 'lostdeposit'){
            $candidateList = Candidate::where(function($query){
                $query->where('parliamentElectionDeposit','=',0)
                ->orWhere('stateElectionDeposit','=',0);
            })->get();
        }
        elseif($selection == 'parliamental'){
            $candidateList = Candidate::select('name','parliamentalConstituency','parliamentElectionDeposit')->orderBy('name')->get();
        }
        elseif($selection == 'state'){
            $candidateList = Candidate::select('name','stateConstituency','stateElectionDeposit')->orderBy('name')->get();

        }
        else{
            $candidateList = Candidate::orderBy('name')->get();
        }
         return view('candidatedeposit')->with(compact('candidateList'));
    }

    public function candidatePartiesView(){
        $parties =(array) Candidate::groupBy('party')->select('party',DB::raw('count(*) as total'))->pluck('total','party')->all();

        $chart = new Chart;
        $chart->labels = (array_keys($parties));
        $chart->dataset = (array_values($parties));

        return view('candidatepartyanalytics')->with(compact('chart'));
    }
}
