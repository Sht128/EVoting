<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionDeposit extends Model
{
    use HasFactory;
    
    /**
     * Connection
     */
    protected $connection = "mysql";

    /**
     * The table name
     */
    protected $table = "election_deposit";
}
