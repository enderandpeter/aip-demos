<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\EventPlanner\User as User;

class RegistrationTest extends TestCase
{
	use DatabaseTransactions;
	
	/**
	 * Test a user's successful registration
	 *
	 * @return void
	 */
	public function testSuccessfulRegistration(){		
		/*
		 * Create a user for registration
		 */
		$clearpass = str_random( 10 );
		$user = factory( User::class )->make([
				'password' => bcrypt( $clearpass )
		]);
		 
		/*
		 * Visit the Registration page and confirm a user can successfully create an account
		 */
		$this->visit( route( 'event-planner.register.show' ) )
		->see( 'E-Mail Address' )
		->type( $user->name, 'name' )
		->type( $user->email, 'email' )
		->type( $clearpass, 'password' )
		->type( $clearpass, 'password_confirmation')
		->press( 'Register' )
		->see( "Hello, $user->name!" );
		 
		$this->assertFalse( User::where( 'name', $user->name )->get()->isEmpty() );
	}
}
