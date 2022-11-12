<?php

namespace App\Http\Controllers;
use Auth;
use Session;
use DB;
use Mail;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\State;
use App\Models\StateDistrict;
use App\Models\ParliamentalDistrict;
use App\Models\VoterToken;
use App\Notifications\SuccessFulVerification;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Encryption\Encrypter;

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
        if($request->electionType == 'Federal Election'){
            $electionSeat = Auth::user()->parliamentalConstituency;
        }
        else{
            $electionSeat = Auth::user()->stateConstituency;
        }
        $candidate = Candidate::where('ic','=',$candidateIc)->first();
        Session::put('electionType',$request->electionType);
        Session::put('candidate',$request->ic);
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
                    return redirect('home')->with('fail', 'Unable to create voter Federal Vote');
                }
            }
            elseif($electionType == 'State Election')
            {
                $updateVote = $this->verifyStateElectionVotingProgress($request);
                if($updateVote){
                    $this->updateStateVotingStatus($request);
                    return redirect('home')->with('success','You have casted your vote!');
                }
                else{
                    return redirect('home')->with('fail', 'Unable to create voter State Vote');
                }
            }
        }
        else{
            return redirect('home')->with('fail', 'Voter Has Already Voted. You are not allowed to cast more than one of same type of vote');
        }
    }

    protected function verifyVoterVoteStatus(Request $request){
        if ($request->electionType == "Federal Election"){
            // Verify User Voting Status
            $voteEligibility = Voter::where('ic','=',Auth::user()->ic)->where('parlimentVoteStatus','=',0)->first();
            return $voteEligibility;
        }
        else{
            $voteEligibility = Voter::where('ic','=',Auth::user()->ic)->where('stateVoteStatus','=',0)->first();
            return $voteEligibility;
        }
    }

    protected function verifyStateElectionVotingProgress(Request $request){
        $isVoted = $this->updateStateElectionVoteStatus();
        // Update Voter Count Success
        if($isVoted){
            $seating = Auth::user()->stateConstituency;
            // Update Voting Status
            $newVote = $this->createVote($request);
            if($newVote){
                $this->updateCandidateVoteCount($request);
                return $this->updateElectionProgress($request,$seating);
            }
            else{
                return false;
            }
        }

    }

    protected function verifyFederalElectionVotingProgress(Request $request){
        $isVoted = $this->updateFederalElectionVoteStatus();
        // Update Voter Count Success
        if($isVoted){
            $seating = Auth::user()->parliamentalConstituency;
            // Update Voting Status
            $newVote = $this->createVote($request);
            if($newVote){
                $this->updateCandidateVoteCount($request);
                return $this->updateElectionProgress($request,$seating);

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
            Voter::where('ic','=',Auth::user()->ic)->increment('parlimentVoteStatus',1);
        }
        else{
            $seating = Auth::user()->stateConstituency;
            Voter::where('ic','=',Auth::user()->ic)->increment('stateVoteStatus',1);
        }

        return Vote::insert([
            'voterId' => encrypt(Auth::user()->ic),
            'candidateId' => encrypt($request->ic),
            'seatingId' => $seating,
            'electionType' => $request->electionType,
        ]);
    }

    protected function updateStateElectionVoteStatus(){
        if(Auth::user()){
            $verified = Voter::where(['ic' => Auth::user()->ic,'is_statevote_verified'=>1])->first();
                if($verified){
                // Find Voter Election Constituency
                $seating = Auth::user()->stateConstituency;
                // Verify If The District Vote Has Not Ended
                $votingDistrict = StateDistrict::where('districtId','=',$seating)->first();
                if($votingDistrict->votingStatus == 0 && ($votingDistrict->currentVoteCount < $votingDistrict->voterTotalCount)){         
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    protected function updateFederalElectionVoteStatus(){
        if(Auth::user()){
            $verified = Voter::where(['ic' => Auth::user()->ic,'is_parlimentvote_verified'=>1])->first();
            if($verified){
                // Find Voter Election Constituency
                $seating = Auth::user()->parliamentalConstituency;
                // Verify If The District Vote Has Not Ended
                $votingDistrict = ParliamentalDistrict::where('districtId','=',$seating)->first();
            
                // Verify Remaining Voter Count
                if($votingDistrict->votingStatus == 0 && ($votingDistrict->currentVoteCount < $votingDistrict->voterTotalCount)){
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    protected function updateCandidateVoteCount(Request $request){
            // Increment Candidate Vote Count
            if ($request->electionType == 'Federal Election'){
                $votes = Vote::where('seatingId','=',Auth::user()->parliamentalConstituency)->get();
                $count = 0;
                foreach($votes as $vote){
                        $candidate  = decrypt($vote->candidateId);
                        if($candidate == $request->ic){
                            $count += 1;
                        }
                }
                 $updateVoteCount = Candidate::where('ic','=',$request->ic)->update(['parliamentalVoteCount' => $count]);

                return $updateVoteCount;
            }
            elseif ($request == 'State Election'){
                $votes = Vote::where('seatingId','=',Auth::user()->stateConstituency)->get();
                $count = 0;
                foreach($votes as $vote){
                    $candidate  = decrypt($vote->candidateId);
                    if($candidate == $request->ic){
                        $count += 1;
                    }
                }
                $updateVoteCount = Candidate::where('ic','=',$request->ic)->update(['stateVoteCount' => $count]);
                return $updateVoteCount;
            }
    }

    protected function updateAllCandidateVoteCount(){

        $candidates = Candidate::get();

        foreach($candidates as $candidate){
            $pvotes = Vote::where('seatingId','=',$candidate->parliamentalConstituency)->get();
            $pCount = 0;
            foreach($pvotes as $vote){
                $vote->candidateId = decrypt($vote->candidateId);
                if($vote->candidateId == $candidate->ic){
                    $pCount += 1;
                }
            }

            $svotes = Vote::where('seatingId','=',$candidate->stateConstituency)->get();
            $sCount = 0;
            foreach($svotes as $vote){
                $vote->candidateId = decrypt($vote->candidateId);
                if($vote->candidateId == $candidate->ic){
                    $sCount += 1;
                }
            }
            Candidate::where('ic','=',$candidate->ic)->update(['parliamentalVoteCount'=>$pCount, 'stateVoteCount'=>$sCount]);
        }
    }
    
    protected function updateElectionProgress($request, $seating){
        $electionType = $request->electionType;
        if($electionType == 'Federal Election'){
            // Update Districts Vote Count
            $updateVoteCount = ParliamentalDistrict::where('districtId','=',$seating)->increment('currentVoteCount',1);
            
            // Update All Candidate Vote Count
            $this->updateAllCandidateVoteCount();

            // Find if collected Votes tally with Registered Vote Amount   
            $voteCount = Vote::where([
                'seatingId' => $seating,

            ])->get()->count();
            $district = ParliamentalDistrict::where('districtId','=',$seating)->first();
            if($voteCount == $district->currentVoteCount){
                if($voteCount == $district->voterTotalCount){ // Check If All Voter in District has Voted
                    $majorityVoteRequired = $district->voterTotalCount / 2;
                    $district->increment('votingStatus',1); // Close District Voting Status
                    $candidate = Candidate::where('parliamentalConstituency' ,'=', $seating
                    )->get();
                    $candidate = $candidate->where('parliamentalVoteCount','>',$majorityVoteRequired)->first();
                    $this->updateCandidateDeposit($request, $seating);
                    if($candidate){
                        // Update Election Winner For Parliamental Constituency
                        $district->update(['majorityCandidate'=>$candidate->ic]);
                        $district->update(['majorityVoteCount'=>$candidate->parliamentalVoteCount]);
                    }
                    else{
                        return false;
                    }
                }
            }
            return $updateVoteCount;
        }
        elseif($electionType == 'State Election'){
            // Update Districts Vote Count
            $updateVoteCount = StateDistrict::where('districtId','=',$seating)->increment('currentVoteCount',1);

            // Update All Candidate Vote Count
            $this->updateAllCandidateVoteCount();

             // Find if collected Votes tally with Registered Vote Amount
             $district = StateDistrict::where('districtId','=',$seating)->first();
             $voteCount = Vote::where([
                 'seatingId' => $seating,
             ])->get()->count();

            if($voteCount == $district->currentVoteCount){
                if($voteCount == $district->voterTotalCount){ // Check If All Voter in District has Voted
                    $majorityVoteRequired = $district->voterTotalCount / 2;
                    $district->increment('votingStatus',1); // Close District Voting Status

                    $candidate = Candidate::where('stateConstituency' ,'=', $seating
                    )->get();
                    $candidate = $candidate->where('stateVoteCount','>',$majorityVoteRequired)->first();
                    $this->updateCandidateDeposit($request, $seating);
                    if($candidate){
                        // Update Election Winner For Parliamental Constituency
                        $district->update(['majorityCandidate'=>$candidate->ic]);
                        $district->update(['majorityVoteCount'=>$candidate->stateVoteCount]);
                    }
                    else{
                        return false;
                    }
                }
            }
            return $updateVoteCount;
        }
    }

    protected function updateStateVotingStatus(Request $request){
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
            $state->increment('votingStatus',1);

            // Announce major coalition result for State Election
            $stateDistricts = StateDistrict::where('stateId','=',Auth::user()->state)->get();
            $stateWinner = DB::table('candidate')
                            ->select('select * from candidate')
                            ->join('statedistrict', 'candidate.ic','=','statedistrict.majorityCandidate')
                            ->where('state','=',$state->stateId)
                            ->get();
            $parties =Candidate::join('statedistrict','candidate.ic','=','statedistrict.majorityCandidate')->groupBy('party')->select('party', DB::raw('count(*) as total'))->where('state','=',$state->stateId)->pluck('total','party')->all();

            $majorityDistrict = $state->stateDistrictCount / 2;
            foreach($parties as $party){
                
                if($party->total > $majorityDistrict){
                    $majorityParty = $party->party;
                }
            }
            
            if($majorityParty){
                $majoritySeats = 0;
                foreach($parties as $party){
                
                    if($party->party == $majorityParty){
                        $majoritySeats += 1;
                    }
                }
            }

            $updateCoalitionWinner = State::where('stateId','=',Auth::user()->state)->update(['majorityCoalition' => $majorityParty, 'result'=>$majoritySeats]);
        }
    }

    protected function updateCandidateDeposit(Request $request, $seating){
        $electionType = $request->electionType;
        if ($electionType == 'Federal Election')
        {
            $seats = ParliamentalDistrict::where('districtId','=',$seating)->first();
            $minimumVote = $seats->voterTotalCount / 8;
            $candidates = Candidate::where('parliamentalConstituency','=',$seating)->where('parliamentalVoteCount','<',$minimumVote)->get();
            foreach($candidates as $candidate){
                $candidate->update(['parliamentElectionDeposit'=>0]);
            }
        }
        elseif( $electionType == 'State Election'){
            $seats = StateDistrict::where('districtId','=',$seating)->first();
            $minimumVote = $seats->voterTotalCount / 8;
            $candidates = Candidate::where('stateConstituency','=',$seating)->where('stateVoteCount','<',$minimumVote)->get();
            foreach($candidates as $candidate){
                $candidate->update(['stateElectionDeposit'=>0]);
            }
        }
    }
}
