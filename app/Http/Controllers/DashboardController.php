<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\State;
use App\Models\StateDistrict;
use App\Models\ParliamentalDistrict;
use DB;

class DashboardController extends Controller
{
    //

    public function candidatedepositView(){
        $candidateList = Candidate::orderBy('name')->get();

        return view('candidatedeposit')->with(compact('candidateList'));
    }
}
