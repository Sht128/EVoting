<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\ElectionDeposit;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'ic' => ['required', 'numeric', 'min:12','unique:voter,ic'],
            'name' => ['required', 'string', 'max:255'],
            
            'state' => ['required', 'string'],
            'district' => ['required', 'string'],
            'postcode' => ['required', 'string'],
            'address' => ['required', 'string'],
            'mobileno' => ['string', 'max:255'],
            'password' => ['required', 'string', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/','confirmed'],
            'usertype' => ['required'],
        ]);

        
    }

    /**
     * 
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
        $this->validator($request->all())->validate();

        $voterAuth = ['ic' => $request->ic, 'name' => $request->name, 'district' => $request->district, 'state' => $request->state, 'postcode' => $request->postcode, 'address' => $request->address];
        $candidateAuth = ['ic' => $request->ic, 'name' => $request->name, 'district' => $request->district, 'state' => $request->state, 'postcode' => $request->postcode, 'address' => $request->address];

        if($request->usertype == 'voter')
        {
            $voterData = (array) DB::connection('mysql2')->table('voter')->where($voterAuth)->first();

            if($voterData){
                event(new Registered($voter = $this->createVoter($voterData, $request->all())));

                $this->guard()->login($voter);

                return $this->registered($request, $voter)
                            ?: redirect($this->redirectPath());
            }
            else{
                return back()->with('fail', 'Your record does not exist. You are required to enter your registered IC number and address.');
            }
        }
        else if($request->usertype == 'candidate'){
            $candidateData = (array) DB::connection('mysql2')->table('candidate')->where($candidateAuth)->first();
            
            if($candidateData){
            event(new Registered($voter = $this->createVoter($candidateData, $request->all())));
            $candidate = $this->createCandidate($candidateData, $request->all());
            $candidateDeposit = $this->updateDepositStatus($candidateData);

            $this->guard()->login($voter);

            return $this->registered($request, $voter)
                        ?: redirect($this->redirectPath());
            }
            else{
                return back()->with('fail', 'Your record does not exist. You are required to enter your registered IC number and address.');
            }
        }
        else{
            return back()->with('fail', 'Unable to identify user type');
        }
    }
    

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Voter
     */
    protected function createVoter(array $firstData, array $secondData)
    {
        return Voter::create([
            'ic' => $firstData['ic'],
            'name' => $firstData['name'],
            'gender' => $firstData['gender'],
            'race' => $firstData['race'],
            'mobileNumber' => $secondData['mobileno'],
            'district' => $firstData['district'],
            'state' => $firstData['state'],
            'postcode' => $firstData['postcode'],
            'address' => $firstData['address'],
            'parliamentalConstituency' => $firstData['parliamentalConstituency'],
            'stateConstituency' => $firstData['stateConstituency'],
            'parlimentVoteStatus' => 0,
            'stateVoteStatus' => 0,
            'password' => Hash::make($secondData['password']),
            'userPrivilege' => '0',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Voter
     */
    protected function createCandidate(array $firstData, array $secondData)
    {
        return $candidate = Candidate::create([
            'ic' => $firstData['ic'],
            'name' => $firstData['name'],
            'mobileNumber' => $secondData['mobileno'],
            'registeredState' => $firstData['state'],
            'parliamentalConstituency' => $firstData['parliamentalConstituency'],
            'stateConstituency' => $firstData['stateConstituency'],
            'party' => $firstData['party'],
            'parliamentElectionDeposit' => 0,
            'stateElectionDeposit' => 0,
            'campaignDeposit' => 0,
        ]);
    }

    protected function updateDepositStatus(array $firstData){
        $depositAll = ElectionDeposit::first();
        $seatings = DB::connection('mysql2')->table('candidate')->select('parliamentalConstituency','stateConstituency')->where('ic','=',$firstData['ic'])->get()->first();
        if(Str::contains($seatings->parliamentalConstituency, 'P')){
            $updateParlimentDeposit = Candidate::where('ic','=',$firstData['ic'])->update(['parliamentElectionDeposit' => $depositAll->parliamentalSeatDeposit]);
        }

        if(Str::contains($seatings->stateConstituency, 'N')){
            $updateStateDeposit = Candidate::where('ic','=',$firstData['ic'])->update(['stateElectionDeposit' => $depositAll->stateSeatDeposit]);
        }

        return Candidate::where('ic','=',$firstData['ic'])->update(['campaignDeposit' => $depositAll->campaignDeposit]);
    }
}
