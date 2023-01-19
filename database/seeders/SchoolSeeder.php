<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create school1
        $school = School::create([
        	'name' => 'SMK Kadeco', 
        	'npsn' => '12345678',
            'education_stage' => 'SMK/SMA',
            'school_status' => 'SWASTA',
            'status' => 'ACTIVE'
        ]);
    }
}
