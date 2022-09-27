<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Voter;
use App\Models\Voters;
use App\Models\Candidate;
use App\Models\Candidates;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'ic' => ['required', 'int', 'min:12','unique:voter,ic'],
            'name' => ['required', 'string', 'max:255'],
            
            'state' => ['required', 'string'],
            'district' => ['required', 'string'],
            'postcode' => ['required', 'string'],
            'address' => ['required', 'string'],
            'email' => ['string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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

        $voterData = (array) Voters::where($voterAuth)->first();

        if($voterData){
            event(new Registered($voter = $this->create($voterData, $request->all())));

            $this->guard()->login($voter);

             return $this->registered($request, $voter)
                        ?: redirect($this->redirectPath());
        }
        else if(!$voterData){
            $candidateData = (array) Candidates::where($voterAuth)->first();
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\Voter
     */
    protected function create(array $firstData, array $secondData)
    {
        return Voter::create([
            'ic' => $firstData['ic'],
            'name' => $firstData['name'],
            'gender' => $firstData['gender'],
            'race' => $firstData['race'],
            'district' => $firstData['district'],
            'state' => $firstData['state'],
            'postcode' => $firstData['postcode'],
            'address' => $firstData['address'],
            'parliamentalConstituency' => $firstData['parliamentalConstituency'],
            'stateConstituency' => $firstData['stateConstituency'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'userPrivilege' => '1',
        ]);
    }
}
