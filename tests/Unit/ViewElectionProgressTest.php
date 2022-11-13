<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voter;
use Database\Seeders\VoterSeeder;
use Database\Seeders\StateDistrictSeeder;
use Database\Seeders\ParliamentalDistrictSeeder;

class ViewElectionProgressTest extends TestCase
{
    /**
     * Test Election Progress Page
     */
    public function test_electionprogress_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('viewelectionprogress'));
        $response->assertStatus(200);
        $response->assertViewIs('viewelectionprogress');
        $response->assertViewHas('state','Ongoing');
        $response->assertViewHas('parliment','Ongoing');
    }

    public function test_parlimentelectionprogress_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('parlimentelectionprogress'));
        $response->assertStatus(200);
        $response->assertViewIs('parliamentalelectionprogress');
        $response->assertViewHas('ongoingStateLists');
        $response->assertViewHas('doneStateLists');
    }

    public function test_stateelectionprogress_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('stateelectionprogress'));
        $response->assertStatus(200);
        $response->assertViewIs('stateelectionprogress');
        $response->assertViewHas('ongoingStateLists');
        $response->assertViewHas('doneStateLists');
    }

    public function test_parliamentalelectionstate_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('parliamentalelectionstate',['ongoingstate'=>'Penang']));
        
        $response->assertViewIs('electionprogressdetails');
        $response->assertViewHas('districts');
        $response->assertViewHas('electiontype', 'Federal Election');
    }

    public function test_stateelectionstate_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('stateelectionstate',['ongoingstate'=>'Penang']));
        
        $response->assertViewIs('electionprogressdetails');
        $response->assertViewHas('districts');
        $response->assertViewHas('electiontype', 'State Election');
    }
    public function test_parliamentalelection_progress(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('electionprogress',['districtId'=>$voter->parliamentalConstituency,'election' => 'Federal Election']));
        
        $response->assertViewIs('electionprogress');
        $response->assertViewHas('chart');
        $response->assertViewHas('district');
    }

    public function test_stateelection_progress(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('electionprogress',['districtId'=>$voter->stateConstituency,'election' => 'State Election']));
        
        $response->assertViewIs('electionprogress');
        $response->assertViewHas('chart');
        $response->assertViewHas('district');
    }
}
