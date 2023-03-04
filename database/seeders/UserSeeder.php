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
        	'email' => 'administrator@apt.com',
        	'password' => bcrypt('123456'),
        ])->syncRoles(['ADMINISTRATOR']);

        // create user2
        $user2 = User::create([
        	'name' => 'Admin', 
        	'email' => 'admin@apt.com',
        	'password' => bcrypt('123456'),
        ])->syncRoles(['ADMIN']);

        // create user3
        $user2 = User::create([
        	'name' => 'Checker', 
        	'email' => 'checker@apt.com',
        	'password' => bcrypt('123456'),
        ])->syncRoles(['CHECKER']);

        // create user4
        $user2 = User::create([
        	'name' => 'User', 
        	'email' => 'user@apt.com',
        	'password' => bcrypt('123456'),
        ])->syncRoles(['USER']);
    }
}
