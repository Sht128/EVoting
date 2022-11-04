<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\MustVerifyMobile;
use App\Interfaces\MustVerifyMobile as IMustVerifyMobile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Voter extends Authenticatable implements MustVerifyEmail
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
    protected $table = 'voter';

    /**
     * Primary Key
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
        'gender',
        'race',
        'mobileNumber',
        'email',
        'email_verified_at',
        'district',
        'state',
        'postcode',
        'address',
        'parliamentalConstituency',
        'stateConstituency',
        'parlimentVoteStatus',
        'stateVoteStatus',
        'is_parlimentvote_verified',
        'is_statevote_verified',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function routeNotificationForVonage($notification){
        return $this->mobileNumber;
    }

    public $timestamps = false;
}
