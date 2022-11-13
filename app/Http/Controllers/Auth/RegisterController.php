<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Voter;
use App\Models\Candidate;
use App\Models\ParliamentalDistrict;
use App\Models\StateDistrict;
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
            'email' => ['required','string'],
            'state' => ['required', 'string'],
            'postcode' => ['required', 'string'],
            'mobileno' => ['string', 'max:255'],
            'password' => ['required', 'string', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/','confirmed'],
        ]);

        
    }

    /**
     * Register New Voter
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request){
        $this->validator($request->all())->validate();

        $voterAuth = ['ic' => $request->ic, 'name' => $request->name, 'state' => $request->state, 'postcode' => $request->postcode];

        // Find Voter Record
        $voterData = (array) DB::connection('mysql2')->table('voter')->where($voterAuth)->first();

        if($voterData){
            $voter=$this->createVoter($voterData, $request->all());
            event(new Registered($voter));
            
            // Update Voter Count in Voter Polling District
            $this->updateStateDistrictVoterCount($voterData);
            $this->updateParliamentalDistrictVoterCount($voterData);

            
            return $this->registered($request, $voter)
                        ?: redirect($this->redirectPath());
        }
        else{
            return back()->with('fail', 'Your record does not exist. You are required to enter your registered IC number and address.');
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
            'email' => $secondData['email'],
            'email_verified_at' => null,
            'district' => $firstData['district'],
            'state' => $firstData['state'],
            'postcode' => $firstData['postcode'],
            'address' => $firstData['address'],
            'parliamentalConstituency' => $firstData['parliamentalConstituency'],
            'stateConstituency' => $firstData['stateConstituency'],
            'parlimentVoteStatus' => 0,
            'stateVoteStatus' => 0,
            'is_parlimentvote_verified' => 0,
            'is_statevote_verified' => 0,
            'password' => Hash::make($secondData['password']),
            'userPrivilege' => '1',
        ]);
    }

    /**
     * Update State Districts Voter Count
     * @param array $data
     */
    protected function updateStateDistrictVoterCount(array $data){
        $voterState = Voter::select('stateConstituency')->where('ic','=', $data['ic'])->first();
        if(!is_null($voterState)){
            $updateVoterCount = StateDistrict::where('districtId','=',$data['stateConstituency'])->increment('voterTotalCount',1);

            return  $updateVoterCount;
        }
    }

    /**
     * Update Parliamental Districts Voter Count
     * @param array $data
     */
    protected function updateParliamentalDistrictVoterCount(array $data){
        $voterState = Voter::select('parliamentalConstituency')->where('ic','=', $data['ic'])->first();
        if(!is_null($voterState)){
            $updateVoterCount = ParliamentalDistrict::where('districtId','=',$data['parliamentalConstituency'])->increment('voterTotalCount',1);

            return  $updateVoterCount;
        }
    }
}
