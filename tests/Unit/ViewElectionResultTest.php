<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voter;

class ViewElectionResultTest extends TestCase
{
    /**
     * Test Election Results Page
     */
    public function test_electionresult_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('viewelectionresults'));
        $response->assertStatus(200);
        $response->assertViewIs('viewelectionresults');
        $response->assertViewHas('parliments');
        $response->assertViewHas('states');
        $response->assertViewHas('federalparties');
        $response->assertViewHas('federalElection','Not Done');
    }

    public function test_statepartiesresult_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('electionpartiesresult',['stateId'=>$voter->state]));
        $response->assertStatus(200);
        $response->assertViewIs('electionprogress');
        $response->assertViewHas('chart');
    }

    public function test_electiondistrictsresult_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('electiondistrictsresult',['districtId'=>$voter->stateConstituency,'electionType' => 'State Election']));
        $response->assertStatus(200);
        $response->assertViewIs('electionprogress');
        $response->assertViewHas('chart');
    }
}
