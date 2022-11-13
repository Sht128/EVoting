<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Voter;

class VoterToken extends Model{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'voter_token';

    protected $fillable = [
        'id',
        'ic',
        'token',
        'type',
    ];

    public $timestamps = false;

    public function voter(){
        return $this->hasMany(Voter::class,'ic');
    }
}