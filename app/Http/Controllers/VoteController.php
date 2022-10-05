<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    //

    public function federalElection(){
        return view('federalElection');
    }

    public function stateElection(){
        return view('stateElection');
    }
}
