<?php

namespace App\Http\Controllers;
use Auth;
use Session;
use DB;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\State;
use App\Models\StateDistrict;
use App\Models\ParliamentalDistrict;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * 
     * @param Request
     * @return Response
     */
    public function federalElection(){
        if(Auth::check()){
            $candidatesList = DB::table('candidate')
                                ->select('candidate.ic','candidate.name','candidate.party','candidate.ic')
                                ->join('voter', 'candidate.parliamentalConstituency', '=', 'voter.parliamentalConstituency')
                                ->where('voter.ic', Auth::user()->ic)
                                ->get();


            return view('federalElection')->with(compact('candidatesList'));
        }
    }

    /**
     * 
     * @param Request
     * @return Response
     */
    public function stateElection(){
        if(Auth::check()){
            $candidatesList = DB::table('candidate')
                                ->select('candidate.ic','candidate.name','candidate.party','candidate.ic')
                                ->join('voter', 'candidate.stateConstituency', '=', 'voter.stateConstituency')
                                ->where('voter.ic', Auth::user()->ic)
                                ->get();


            return view('stateElection')->with(compact('candidatesList'));
        }
    }

    /**
     * @param Request
     * @return Response
     */
    public function voteConfirmation(Request $request){
        $candidateIc = $request->ic;
        $partyList = ['PN' => 'Perikatan Nasional', 'PH' => 'Pakatan Harapan', 'BN' => 'Barisan Nasional', 'MUDA' => 'Malaysian United Democratic Alliance', 'Independent' => 'Independent'];
        if($request->electionType == 'federalElection'){
            $electionSeat = Auth::user()->parliamentalConstituency;
        }
        else{
            $electionSeat = Auth::user()->stateConstituency;
        }
        $candidate = Candidate::where('ic','=',$candidateIc)->first();

        $candidate->party = $partyList[$candidate->party];
        return view('voteConfirmation')->with(compact('candidate'))->with('seating', $electionSeat)->with('electionType',$request->electionType)->with('party',$partyList);
    }

    /**
     * 
     * @param Request
     * @return Response
     */
    public function vote(Request $request){
        if($this->verifyVoterVoteStatus($request)){
            $electionType = $request->electionType;
            
            // Check for Election Type
            if($electionType == 'Federal Election'){
                $updateVote = $this->verifyFederalElectionVotingProgress($request);
                if($updateVote){
                
                    $this->updateStateVotingStatus($request);
                    return redirect('home')->with('success','You have casted your vote!');
                }
                else{
                    return redirect('voteConfirmation')->with('fail, Unable to create voter vote');
                }
            }
            else
            {
                $updateVote = $this->verifyStateElectionVotingProgress($request);
                if($updateVote){
                    $this->updateStateVotingStatus($request);
                    return redirect('home')->with('success','You have casted your {{$request->electionType}} vote!');
                }
                else{
                    return redirect('home')->with('fail', 'Unable to create voter vote')->with('seating',Auth::user()->stateConstituency);
                }
            }
        }
        else{
            return redirect('home')->with('fail', 'Voter Has Already Voted. You are not allowed to cast more than one of same type ofvote');
        }
    }

    protected function verifyVoterVoteStatus(Request $request){
        if ($request->electionType == "Federal Election"){
            // Verify User Voting Status
            $voteEligibility = Voter::where('ic','=',Auth::user()->ic)->where('parliamentalVoteStatus','=',0)->first();
            return $voteEligibility;
        }
        else{
            $voteEligibility = Voter::where('ic','=',Auth::user()->ic)->where('stateVoteStatus','=',0)->first();
            return $voteEligibility;
        }
    }

    protected function verifyStateElectionVotingProgress(Request $request){
        $isVoted = $this->updateStateElectionVoteProgress($request);
        // Update Voter Count Success
        if($isVoted){
            // Update Voting Status
            $newVote = $this->createVote($request);
            if($newVote){
                return $this->updateCandidateVoteCount($request);
            }
            return true;
        }

    }

    protected function verifyFederalElectionVotingProgress(Request $request){
        $isVoted = $this->updateFederalElectionVoteProgress($request);
        // Update Voter Count Success
        if($isVoted){
            // Update Voting Status
            $newVote = $this->createVote($request);
            if($newVote){
                return $this->updateCandidateVoteCount($request);
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    protected function createVote(Request $request){
        if($request->electionType == 'Federal Election'){
            $seating = Auth::user()->parliamentalConstituency;
            $updateUserVoteStatus = Voter::where('ic','=',Auth::user()->ic)->increment('parlimentVoteStatus',1);
        }
        else{
            $seating = Auth::user()->stateConstituency;
            $updateUserVoteStatus = Voter::where('ic','=',Auth::user()->ic)->increment('parlimentVoteStatus',1);
        }

        $user = Auth::user();
        return Vote::insert([
            'voterId' => Auth::user()->ic,
            'candidateId' => $request->ic,
            'seatingId' => $seating,
            'electionType' => $request->electionType,
        ]);
    }

    protected function updateStateElectionVoteProgress(Request $request){
        if(Auth::user()){
            $userIc = Auth::user()->ic;
            // Find Voter Election Constituency
            $seating = Auth::user()->stateConstituency;
            if($seating){
                // Verify If The District Vote Has Not Ended
                $votingDistrict = StateDistrict::where('districtId','=',$seating)->first();
                if($votingDistrict->votingStatus == 0 && $votingDistrict->currentVoteCount < $votingDistrict->voterTotalCount){
                    $votingDistrict->increment('currentVoteCount',1);
                    // Get New Vote Count
                    $newVoteCount = StateDistrict::where('districtId','=',$seating)->first();

                    if($newVoteCount->currentVoteCount == $newVoteCount->totalVoterCount){
                        // Close District Voting Status
                        StateDistrict::where('districtId','=',$districtId)->increment('votingStatus',1); 
                        // Update Majority Vote Count
                        $majorityCandidate  = DB::table('candidate')
                                              ->select('candidate.ic','candiate.stateVoteCount')
                                              ->join('statedistrict', 'candidate.ic','=','statedistrict.majorityCandidate')
                                              ->where('candidate.stateConstituency','=',$seating)
                                              ->first();
                        StateDistrict::where('districtId','=',$seating)->update(['majorityVoteCount' => $majorityCandidate->stateVotecount]);
                    }                   
                    return true;
                }
                else{
                    return false;
                }
            }
        }
    }

    protected function updateFederalElectionVoteProgress(Request $request){
        if(Auth::user()){
            $userIc = Auth::user()->ic;
            // Find Voter Election Constituency
            $seating = Auth::user()->parliamentalConstituency;
            if($seating){
                // Verify If The District Vote Has Not Ended
                $votingDistrict = ParliamentalDistrict::where('districtId','=',$seating)->first();
            
                // Verify Remaining Voter Count
                if($votingDistrict->votingStatus == 0 && $votingDistrict->currentVoteCount < $votingDistrict->voterTotalCount){
                    $votingDistrict->increment('currentVoteCount',1);
                    // Get New Vote Count
                    $newVoteCount = ParliamentalDistrict::where('districtId','=',$seating)->first();

                    if($newVoteCount->currentVoteCount == $newVoteCount->totalVoterCount){
                        // Close District Voting Status
                        ParliamentalDistrict::where('districtId','=',$districtId)->increment('votingStatus',1); 
                        // Update Majority Vote Count
                        $majorityCandidate  = DB::table('candidate')
                                              ->select('candidate.ic','candiate.parliamentalVoteCount')
                                              ->join('parliamentaldistrict', 'candidate.ic','=','parliamentaldistrict.majorityCandidate')
                                              ->where('candidate.parliamentalConstituency','=',$seating)
                                              ->first();;

                        ParliamentalDistrict::where('districtId','=',$seating)->update(['majorityVoteCount'=>$majorityCandidate->parliamentalVoteCount]);
                        return true;
                    }
                    return false;
                }
                else{
                    return false;
                }
            }
        }
    }

    protected function updateCandidateVoteCount(Request $request){
        if (Auth::user()){

            // Increment Candidate Vote Count
            if ($request->electionType == 'Federal Election'){
                $updateVoteCount = Candidate::where('ic','=',$request->ic)->increment('parliamentalVoteCount',1);
                return $updateVoteCount;
            }
            else{
                $updateVoteCount = Candidate::where('ic','=',$request->ic)->increment('stateVoteCount',1);
                return $updateVoteCount;
            }
        }
    }
    
    protected function updateElectionWinner(Request $request){
        if (Auth::user()){
            $electionType = $request->electionType;
            if($electionType == 'Federal Election'){
                $seating = Auth::user()->parliamentalConstituency;

                // Find If Candidate Has Gathered Majority Vote
                $totalVoterCount = ParliamentalDistrict::select('totalVoterCount')->where('districtId','=',$seating)->first();
                $majorityVoteRequired = $totalVoterCount / 2;
                $candidate = Candidate::where(
                    ['parliamentalConstituency','=', $seating],
                    ['parliamentalVoteCount','>',$majorityVoteRequired],
                )->first();

                if($candidate){
                    // Update Election Winner For Parliamental Constituency
                    $setWinner = ParliamentalDistrict::where('districtId','=',$seating)->update(['majorityCandidate','=',$candidate->ic]);
                }
            }
            else{
                $seating = Auth::user()->stateConstituency;

                // Find If Candidate Has Gathered Majority Vote
                $totalVoterCount = StateDistrict::select('totalVoterCount')->where('districtId','=',$seating)->first();
                $majorityVoteRequired = $totalVoterCount / 2;
                $candidate = Candidate::where(
                    ['stateConstituency','=', $seating],
                    ['stateVoteCount','>',$majorityVoteRequired],
                )->first();

                if($candidate){
                    // Update Election Winner For Parliamental Constituency
                    $setWinner = StateDistrict::where('districtId','=',$seating)->update(['majorityCandidate','=',$candidate->ic]);
                }
            }
        }
    }

    protected function updateStateVotingStatus(Request $request){
        if (Auth::user()){
            // Check total constituencies that has completed voting
            $parliamentalCount = ParliamentalDistrict::select('votingStatus')->where([
                'votingStatus' => 1,
                'stateId' => Auth::user()->state,
                ])->count();

            $stateCount = StateDistrict::select('votingStatus')->where([
                'votingStatus' => 1,
                'stateId' => Auth::user()->state,
                ])->count();

            // Get Total Districts Count
            $state = State::where('stateId','=',Auth::user()->state)->first();

            if($parliamentalCount == $state->parliamentalDistrictCount && $stateCount == $state->stateDistrictCount){
                // Update State Voting Status As Done
                State::where('stateId','=',Auth::user()->state)->increment('votingStatus',1);

                // Announce major coalition result for State Election
                $stateDistricts = StateDistrict::where('stateId','=',Auth::user()->state)->get();
                $stateWinner = DB::table('candidate')
                               ->select('select * from candidate')
                               ->join('statedistrict', 'candidate.ic','=','statedistrict.majorityCandidate')
                               ->where('state','=',$state->stateId)
                               ->get();

                // Get Each Party Count
                $majorityParty = $stateWinner->select('party')->distinct()->get();
                foreach($majorityParty as $party){
                    $partyWinCount = $stateWinner->where('party','=',$party)->count();
                    if($partyWinCount > ($state->stateDistrictCount/2)){
                        return $majorityParty = $party;
                    }
                }
                
                $updateCoalitionWinner = State::where('stateId','=',Auth::user()->state)->update(['majorityCoalition' => $majorityPaarty]);



            }
        }
    }
}
