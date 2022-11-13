<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voter;
use Candidate;

class ViewElectionCandidateTest extends TestCase
{
    public function test_federalelection_page(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('federalElectionPage'));
        $response->assertStatus(200);
        $response->assertViewIs('federalElection');
        $response->assertViewHas('candidatesList');
    }

    public function test_stateelection_page(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('stateElectionPage'));
        $response->assertStatus(200);
        $response->assertViewIs('stateElection');
        $response->assertViewHas('candidatesList');
    }
}
