<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voter;

class HomeTest extends TestCase
{
    /**
     * Test Home View
     */
    public function test_home_page(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /**
     * Test Voting Count
     */
    public function test_vote_count(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('home'));
        $response->assertSessionHas('userVoteCount',2);
    }

    /**
     * Voter Profile
     */
    public function test_voter_profile(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('voterprofile'));
        $response->assertStatus(200);
        $response->assertViewIs('voterprofile');
        $response->assertViewHas('federalCandidate');
        $response->assertViewHas('stateCandidate');
    }
}
