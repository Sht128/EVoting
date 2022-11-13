<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParliamentalDistrict extends Model{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'parliamentaldistrict';

    protected $primaryKey = 'districtId';

    protected $fillable = [
        'districtId',
        'stateId',
        'votingStatus',
        'voterTotalCount',
        'currentVoteCount',
        'majorityVoteCount',
        'majorityCandidate',
    ];

    public function state(){
        return $this->belongsTo(State::class,'stateId');
    }

    protected $casts = [
        'voterTotalCount' => 'integer',
        'currentVoteCount' => 'integer',
        'majorityVoteCount' => 'integer',
    ];

    public $timestamps = false;

    public $incrementing = false;
}