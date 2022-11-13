<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class VotersSeeder extends Seeder
{
    protected $connection = 'mysql2';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql2')->table('voter')->insert([
            'ic' => '123456789123',
            'name' => 'Teoh',
            'gender' => 'male',
            'race' => 'chinese',
            'district' => 'Bayan Baru',
            'state' => 'Penang',
            'postcode' => '11900',
            'address' => 'Jalan ABC',
            'parliamentalConstituency' => 'P053 Balik Pulau',
            'stateConstituency' => 'N38 Bayan Lepas',
        ]);
    }
}
