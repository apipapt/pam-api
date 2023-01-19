<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create user1
        $user1 = User::create([
        	'name' => 'Administrator', 
        	'email' => 'admin@gmail.com',
        	'password' => bcrypt('123456'),
        ])->syncRoles(['ADMIN']);

        // create user1
        $user2 = User::create([
        	'name' => 'Admin Sekolah', 
        	'email' => 'smk@gmail.com',
        	'password' => bcrypt('123456'),
        ])->syncRoles(['SCHOOL']);
    }
}
