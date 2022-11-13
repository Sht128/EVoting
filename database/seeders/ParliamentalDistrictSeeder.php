<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ParliamentalDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statedistrict')->insert([
            'districtId' => 'N38 Bayan Lepas',
            'stateId' => 'Penang',
            'voterTotalCount' => 1,
            'currentVoteCount' => 0,
            'votingStatus' => 0,
            'majorityVoteCount' => 0,
            'majorityCandidate' => null,
        ]);
    }
}
