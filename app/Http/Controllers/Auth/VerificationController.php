<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VoteController;
use App\Mail\VoteMail;
use App\Models\Voter;
use App\Models\VoterToken;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Verified;
use Illuminate\Encryption\Encrypter;
use Vonage;
use Auth;
use League\CommonMark\Extension\SmartPunct\Quote;
use Mail;
use Session;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

 

    /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'verify' => ['required', 'numeric'],
        ]);

        
    }

    /**
     * Sends Vote Verification Code Email
     * 
     * @param Request $request
     * @return $response
     */
    public function verifyVoteView(Request $request){
        // Generate Random Verification Code
        $code = mt_rand(1,9999);

        if($request->electionType == 'Federal Election'){
            $type = 'Federal Election';
        }
        else{
            $type = 'State Election';
        }
        
        // Create new Voter Token
        VoterToken::create([
            'ic' => Auth::user()->ic,
            'token' => encrypt($code),
            'type' => $type,
        ]);

        if(Session::has('resent')){
            Session::pull('resent');
        }

        // Send Verification Code Email
        Mail::to(Auth::user()->email)->send(new VoteMail($code));
        
        return view('verifyvote')->with('electionType',$request->electionType)->with('ic',$request->ic);
    }

    /**
     * Verify Received Authentication Code and Authenticate Voter
     * 
     * @param Request $request
     * @return $response
     */
    public function verifyCode(Request $request){
        // Validate Verificaation Code
        $this->validator($request->all())->validate();
        $election = $request->electionType;
        $verified = false;
        $tokens = VoterToken::where([
            'ic'=>Auth::user()->ic,
             'type'=>$election,])->get();
             
        foreach($tokens as $token){
            $code = decrypt($token->token);
            if($code == $request->verify){
                $verified = true;
            }
        }

        // Authenticate Voter to Cast Vote
        if($verified){
            $voter = Voter::where('ic','=',Auth::user()->ic)->first();
            $vote = new VoteController();
            $info = new Request();
            $info->ic = $request->ic;
            $info->electionType = $election;

            if($election == 'Federal Election'){
                if($voter->is_parlimentvote_verified == 0){
                    $voter->increment('is_parlimentvote_verified',1);
                    $message = 'Your identity have been authenticated. You are now able to vote';
                    return $vote->vote($info);
                }
                else{
                    $message = 'You have already verified for this vote.';
                }
            }
            else{
                if($voter->is_statevote_verified == 0){
                    $voter->increment('is_statevote_verified',1);
                    $message = 'Your identity have been authenticated. You are now able to vote';
                    return $vote->vote($info);
                }
                else{
                    $message = 'You have already verified for this vote.';
                }
            }
            
        }
        else{
            $message = 'Unable to authenticate your identity';
            return redirect()->route('home')->with('fail',$message);
        }
        
    }

    /**
     * Resend Vote Verification Code Email
     * 
     * @param Request $request
     * @return $response
     */
    public function resendEmail(Request $request){
        // Generated Random Verification Code
        $code = mt_rand(1,9999);

        if($request->electionType == 'Federal Election'){
            $type = 'Federal Election';
        }
        else{
            $type = 'State Election';
        }

        // Create new Voter Token
        VoterToken::create([
            'ic' => Auth::user()->ic,
            'token' => encrypt($code),
            'type' => $type,
        ]);

        Session::put('resent', 'Email has been resent'); // Set Resent Email Session Data

        

        return redirect()->route('verifyVote')->with('request');
    }
}
