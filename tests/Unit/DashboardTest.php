<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voter;

class DashboardTest extends TestCase
{
    /**
     * Test Dashboard Page
     */
    public function test_dashboard_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('dashboardhome');
    }

    public function test_candidatedeposit_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('candidatedepositpage'));
        $response->assertStatus(200);
        $response->assertViewIs('candidatedeposit');
        $response->assertViewHas('candidateList');
    }

    /**
     * Test Candidate Deposit Filter
     * @dataProvider validRequestProvider
     */
    public function test_candidatedeposit_filter($filter){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->from('candidtedeposit')->post(route('candidatedepositfilter',['depositfilter'=>$filter]));
        $response->assertStatus(200);
        $response->assertViewIs('candidatedeposit');
        $response->assertViewHas('candidateList');
    }


    public function test_raceanalytic_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('raceanalytic'));
        $response->assertStatus(200);
        $response->assertViewIs('raceanalytics');
    }

    public function test_allelections_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('allelections'));
        $response->assertStatus(200);
        $response->assertViewIs('allelectionanalytics');
        $response->assertViewHas('parliamentals');
        $response->assertViewHas('states');
    }

    public function test_candidateparty_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('candidateparty'));
        $response->assertStatus(200);
        $response->assertViewIs('candidatepartyanalytics');
        $response->assertViewHas('chart');
    }

    public function test_candidatelist_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('candidatelist'));
        $response->assertStatus(200);
        $response->assertViewIs('allcandidatelist');
        $response->assertViewHas('candidates');
    }

    public function test_electionresults_view(){
        $voter = Voter::factory()->make();

        $response = $this->actingAs($voter)->get(route('electionresults'));
        $response->assertStatus(200);
        $response->assertViewIs('electionresultdashboard');
        $response->assertViewHas('state', 'Ongoing');
        $response->assertViewHas('parliment', 'Ongoing');
    }






    public function validRequestProvider(){
        return array(
            array('done'),
            array('lostdeposit'),
            array('parliamental'),
            array('state'),
        );
    }
}
