<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Candidate extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 
     * 
     * $connection
     */
    protected $connection = 'mysql';

    /**
     * The table name
     */
    protected $table = 'candidate';

    /**
     * 
     */
    protected $primaryKey = 'ic';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ic',
        'name',
        'mobileNumber',
        'registeredState',
        'parliamentalConstituency',
        'stateConstituency',
        'party',
        'parlimentVoteStatus',
        'stateVoteStatus',
        'parliamentElectionDeposit',
        'stateElectionDeposit',
        'campaignDeposit',
        'password',
        'userPrivilege',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false;
}
