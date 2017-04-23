<?php

namespace Tests\Browser\EventPlanner;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\EventPlanner\User as User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class RegistrationTest extends DuskTestCase
{
	use DatabaseMigrations;
	/**
	 * Make sure empty fields return the expected errors
	 *
	 * @return void
	 */
	public function testRequiredFields()
	{
		$this->browse(function (Browser $browser) {
			$browser->visit( route( 'event-planner' ) )
			->assertSee( 'Welcome to Event Planner!' );
				
			/*
			 * Confirm the error text when submitting empty fields to the registration form
			 */
			$browser->visit( route( 'event-planner.register.show' ) )
				->type( 'name', '' )
				->type( 'email', '' )
				->type( 'password', '' )
				->type( 'password_confirmation', '' )
				->assertSee( 'The name field is required.' )
				->assertSee( 'The email field is required.' )
				->assertSee( 'The password field is required.' )
				->press( 'Register' )
				->assertSee( 'The name field is required.' )
				->assertSee( 'The email field is required.' )
				->assertSee( 'The password field is required.' );
			$this->assertNull( User::first() );			
			
		});		
		
	}
	
	/**
	 * Test the maximum length restraints for the registration form
	 *
	 * @TODO Find a way to confirm maxlength message
	 * @return void
	 */
	public function testFieldLengths(){
		$this->browse(function (Browser $browser) {
			/*
			 * Visit the registration page and enter fields that are too long
			 */
			$browser->visit( route( 'event-planner.register.show' ) )
				->assertSee('E-Mail Address');
				
			/*
			 * Confirm the error text when submitting oversized data
			 */
			$clearpass = str_random( 7 );
			$shortclearpass = str_random( 3 );
			$name = str_random( 256 );
			$browser->type( 'name', $name )
				->type( 'email', str_random( 256 ) . '@example.com' )
				->type( 'password', $clearpass )
				->type( 'password_confirmation', $clearpass )
				//->assertSee( 'The name may not be greater than 255 characters.' )
				->assertSee( 'The email must be a valid email address.' )
				//->assertSee( 'The email may not be greater than 255 characters.' )
				->press( 'Register' )
				->assertSee( 'The email must be a valid email address.' )
				->type( 'password', $shortclearpass )
				->assertSee( 'The password must be at least 6 characters.' )
				->type( 'password_confirmation', $shortclearpass )
				->press( 'Register' )
				->assertSee( 'The password must be at least 6 characters.' );
			$this->assertTrue( User::where( 'name', $name )->get()->isEmpty() );			
		});
	}
	
	/**
	 * Test the rules for validating the password
	 * 
	 * @return void
	 */
	public function testPasswordValidation(){
		$this->browse(function (Browser $browser) {		
			$password = str_random( 7 );
			$password_confirmation = str_random( 8 );
		
			$browser->visit( route( 'event-planner.register.show' ) )
				->type( 'password', $password )
				->type( 'password_confirmation', $password_confirmation )
				->assertSee('The password confirmation does not match')
				->press( 'Register' )
				->assertSee('The password confirmation does not match');		
			$this->assertNull( User::first() );
		});
	}
	
	/**
	 * Test a user's successful registration	 
	 * @return void
	 */
	public function testSuccessfulRegistration(){
		/*
		 * Visit the Registration page and confirm a user can successfully create an account
		 */
		$this->browse(function (Browser $browser) {
			/*
			 * Create a user for registration
			 */
			$clearpass = str_random( 10 );
			$user = factory( User::class )->make([
					'password' => bcrypt( $clearpass )
			]);
			
			$browser->visit( route( 'event-planner.register.show' ) )
				->assertSee( 'E-Mail Address' )
				->type( 'name', $user->name )
				->type( 'email', $user->email )
				->type( 'password', $clearpass )
				->type( 'password_confirmation', $clearpass)
				->press( 'Register' )
				->assertSee( "Hello, $user->name!" );				
			$this->assertFalse( User::where( 'name', $user->name )->get()->isEmpty() );
			
			$user->delete();
		});
		
	}
}
