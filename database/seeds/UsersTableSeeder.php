<?php

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
    	DB::table('eventplanner_users')->insert([
    			'name' => 'Spencer Williams',
    			'email' => 'enderandpeter@yahoo.com',
    			'password' => bcrypt('password'),
    			'created_at' => Carbon::now()
    	]);
    }
}
