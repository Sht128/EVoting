<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'state';

    protected $primaryKey = 'stateId';

    protected $fillable = [
        'stateId',
        'parliamentalDistrictCount',
        'stateDistrictCount',
        'stateVotingStatus',
        'majorityCoalition',
        'result',
    ];

    public $incrementing = false;

    protected $casts = [
        'parliamentalDistrictCount' => 'integer',
        'stateDistrictCount' => 'integer',
        'majorityVoteCount' => 'integer',
    ];

    public function stateConstituencies(){

        return $this->hasMany(StateDistrict::class, 'stateId');
    }

    public function parliamentalConstituencies(){
        return $this->hasMany(ParliamentalDistrict::class, 'stateId');
    }

}