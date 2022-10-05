<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 
     * Loads Home User Data
     */
    public function home(){
        $userVoteCount = 0;
        if(Auth::check()){
            if(Auth::user()->parlimentVoteStatus == 0){
                $userVoteCount = $userVoteCount + 1;
            }
            if(Auth::user()->stateVoteStatus == 0){
                $userVoteCount = $userVoteCount + 1;
            }
        }
        Session::put('userVoteCount', $userVoteCount);

        return view('home');
     }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
