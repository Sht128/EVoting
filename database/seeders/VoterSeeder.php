<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VoterSeeder extends Seeder
{

    protected $connection = 'mysql';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('voter')->insert([
            'ic' => '123123089678',
            'name' => 'Hello There',
            'gender' => 'male',
            'race' => 'chinese',
            'mobileNumber' => '+0123456745',
            'email' => 'hello@example.com',
            'email_verified_at' => null,
            'district' => 'Telok Mas',
            'state' => 'Melaka',
            'postcode' => '11900',
            'address' => 'Jalan ABC',
            'parliamentalConstituency' => 'P138 Kota Melaka',
            'stateConstituency' => 'N23 Telok Mas',
            'parlimentVoteStatus' => 0,
            'stateVoteStatus' => 0,
            'is_parlimentvote_verified' => 0,
            'is_statevote_verified' => 0,
            'password' => Hash::make('ABCD@qw123'),
            'userPrivilege' => 0,
        ]);
    }
}
