<?php

namespace Tests\Unit;

use App\Http\Controllers\VoteController;
use Tests\TestCase;
use App\Models\Voter;
use Database\Seeders\VotersSeeder;

class VotingTest extends TestCase
{
    /**
     * Test Vote Confirmation Page
     * @dataProvider federalCandidateDataProvider
     */
    public function test_federalcandidate_confirmation($ic, $election){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('voteConfirmation', ['ic'=>$ic,'electionType'=>$election]));
        $response->assertViewIs('voteConfirmation');
        $response->assertViewHas('candidate');
        $response->assertViewHas('seating', 'P053 Balik Pulau');
        $response->assertViewHas('electionType', 'Federal Election');
    }

    /**
     * Test Vote Confirmation Page
     * @dataProvider stateCandidateDataProvider
     */
    public function test_statecandidate_confirmation($ic, $election){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('voteConfirmation', ['ic'=>$ic,'electionType'=>$election]));
        $response->assertViewIs('voteConfirmation');
        $response->assertViewHas('candidate');
        $response->assertViewHas('seating', 'N38 Bayan Lepas');
        $response->assertViewHas('electionType', 'State Election');
    }

    public function federalCandidateDataProvider(){
        return array(
            array('810516072251','Federal Election')
        );
    }

    public function stateCandidateDataProvider(){
        return array(
            array('810516072251','State Election')
        );
    }
}
