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
    public function candidatedepositView(){
        $candidateList = Candidate::orderBy('name')->get();

        return view('candidatedeposit')->with(compact('candidateList'));
    }

    public function raceAnalyticsView(){
        return view('raceanalytics');
    }

    public function allElectionsView(){
        $parliamentals = ParliamentalDistrict::get();
        $states = StateDistrict::get();

        return view('allelectionanalytics')->with(compact('parliamentals'))->with(compact('states'));
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

    public function allVoterRaceView(){
        $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->pluck('total','race')->all();

        $chart = new Chart;
        $chart->labels = (array_keys($voters));
        $chart->dataset = (array_values($voters));

        return view('allvoterrace')->with(compact('chart'));
    }

    public function parliamentVoterRaceView(Request $request){
        $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->where('parliamentalConstituency','=',$request->constituency)->pluck('total','race')->all();

        $chart = new Chart;
        $chart->labels = (array_keys($voters));
        $chart->dataset = (array_values($voters));

        return view('parliamentvoterrace')->with(compact('chart'));
    }

    public function stateVoterRaceView(Request $request){
        $voters =(array) Voter::groupBy('race')->select('race', DB::raw('count(*) as total'))->where('stateConstituency','=',$request->constituency)->pluck('total','race')->all();

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

    public function candidateDepositFilter(Request $request){
        $selection = $request->depositfilter;

        if($selection == 'ongoing'){
            $candidateList = Candidate::join('state','state.stateId','=','registeredState')->where('state.stateVotingStatus','=',0)->get();
            return view('candidatedeposit')->with(compact('candidateList'));
        }
        else if($selection == 'done'){
            $candidateList = Candidate::join('state','state.stateId','=','registeredState')->where('state.stateVotingStatus','=',1)->get();
            return view('candidatedeposit')->with(compact('candidateList'));
        }
        else if($selection == 'lostdeposit'){
            $candidateList = Candidate::where(function($query){
                $query->where('parliamentElectionDeposit','=',0)
                ->orWhere('stateElectionDeposit','=',0);
            })->get();
            return view('candidatedeposit')->with(compact('candidateList'));
        }
    }
}
