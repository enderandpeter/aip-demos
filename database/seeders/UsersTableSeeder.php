<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
    	DB::table('eventplanner_users')->insert([
    			'name' => 'Spencer Williams',
    			'email' => 'enderandpeter@yahoo.com',
    			'password' => bcrypt('password'),
    			'created_at' => Carbon::now()
    	]);
    }
}
