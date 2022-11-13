<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;
    
    /**
     * Connection
     */
    protected $connection = "mysql";

    /**
     * The table name
     */
    protected $table = "vote";

    /**
     * Fillable
     */
    protected $fillable = [
        'voteId',
        'voterId',
        'candidateId',
        'seatingId',
        'electionType',
        'electionCycleId',
    ];

    public $timestamps = false;
}
