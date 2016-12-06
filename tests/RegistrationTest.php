<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\EventPlanner\User as User;

class RegistrationTest extends TestCase
{
	use DatabaseMigrations;
	/**
	 * Make sure empty fields return the expected errors
	 *
	 * @return void
	 */
	public function testRequiredFields()
	{
		/*
		 * Visit the site with middleware enabled. The user should see the generic greeting.
		 */
		$this->visit( route( 'event-planner' ) )
		->see( 'Welcome to Event Planner!' );
		 
		/*
		 * Confirm the error text when submitting empty fields to the registration form
		 */
		$this->visit( route( 'event-planner.register.show' ) )
		->type( '', 'name' )
		->type( '', 'email' )
		->type( '', 'password' )
		->type( '', 'password_confirmation' )
		->press( 'Register' )
		->see( 'The name field is required.' )
		->see( 'The email field is required.' )
		->see( 'The password field is required.' );
		$this->assertNull( User::first() );
		 
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		 
		$this->post( route( 'event-planner.register.post' ), [
				'name' => '',
				'email' => '',
				'password' => '',
				'password_confirmation' => '',
				'_token' => csrf_token()
		])
		->assertRedirectedTo( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( ['name', 'email', 'password'] );
		$this->assertNull( User::first() );
	}
	
	/**
	 * Test the maximum length restraints for the registration form
	 *
	 * @return void
	 */
	public function testMaxLength(){
		/*
		 * Visit the registration page and enter fields that are too long
		 */
		$this->visit( route( 'event-planner.register.show' ) )
		->see('E-Mail Address');
		 
		/*
		 * Confirm the error text when submitting oversized data
		 */
		$clearpass = str_random( 7 );
		$name = str_random( 256 );
		$this->type( $name , 'name' )
		->type( str_random( 256 ) . '@example.com', 'email' )
		->type( $clearpass, 'password' )
		->type( $clearpass, 'password_confirmation' )
		->press( 'Register' )
		->see( 'The name may not be greater than 255 characters.' )
		->see( 'The email must be a valid email address.' )
		->see( 'The email may not be greater than 255 characters.' )
		->assertTrue( User::where( 'name', $name )->get()->isEmpty() );
		 
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		 
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => str_random( 256 ) . '@example.com',
				'password' => $clearpass,
				'password_confirmation' => $clearpass,
				'_token' => csrf_token()
		])
		->assertRedirectedTo( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( ['name', 'email'] );
		 
		$this->assertTrue( User::where( 'name', $name )->get()->isEmpty() );
	}
	
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
