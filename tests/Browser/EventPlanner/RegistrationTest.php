<?php

namespace Tests\Browser\EventPlanner;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\EventPlanner\User;
use Illuminate\Support\Str;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;
use Throwable;

class RegistrationTest extends DuskTestCase
{
	use DatabaseMigrations, ValidatesEventPlannerRequests;

    /**
     * Make sure empty fields return the expected errors
     * @group register-required
     * @return void
     * @throws Throwable
     */
	public function testRequiredFields()
	{
		$this->browse(function ( Browser $browser ) {
			$validationArray = $this->getValidationMessagesArray( 'register' );

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
				->assertSee( $validationArray[ 'name' ][ 'required' ] )
				->assertSee( $validationArray[ 'email' ][ 'required' ] )
				->assertSee( $validationArray[ 'password' ][ 'required' ] )
				->press( 'Register' )
				->assertSee( $validationArray[ 'name' ][ 'required' ] )
				->assertSee( $validationArray[ 'email' ][ 'required' ] )
				->assertSee( $validationArray[ 'password' ][ 'required' ] );
			$this->assertNull(User::first() );

		});

	}

    /**
     * Test the field length restraints for the registration form
     *
     * @TODO Find a way to confirm maxlength message
     * @group register-fieldlengths
     * @return void
     * @throws Throwable
     */
	public function testFieldLengths(){
		$this->browse(function ( Browser $browser ) {
			$validationArray = $this->getValidationMessagesArray( 'register' );

			/*
			 * Visit the registration page and enter fields that are too long
			 */
			$browser->visit( route( 'event-planner.register.show' ) )
				->assertSee('E-Mail Address');

			/*
			 * Confirm the error text when submitting oversized data
			 */
			$clearpass = Str::random( 7 );
			$shortclearpass = Str::random( 3 );
			$name = Str::random( 256 );
			$browser->type( 'name', $name )
				->type( 'email', Str::random( 256 ) . '@example.com' )
				->type( 'password', $clearpass )
				->type( 'password_confirmation', $clearpass )
				//->assertSee( 'The name may not be greater than 255 characters.' )
				->assertSee( $validationArray[ 'email' ][ 'email' ] )
				//->assertSee( 'The email may not be greater than 255 characters.' )
				->press( 'Register' )
				->assertSee( $validationArray[ 'email' ][ 'email' ] )
				->type( 'password', $shortclearpass )
				->assertSee( $validationArray[ 'password' ][ 'min' ] )
				->type( 'password_confirmation', $shortclearpass )
				->press( 'Register' )
				->assertSee( $validationArray[ 'password' ][ 'min' ] );
			$this->assertTrue(User::where('name', $name )->get()->isEmpty() );
		});
	}

    /**
     * Test the rules for confirming the password
     * @group register-passwordconfirmation
     * @return void
     * @throws Throwable
     */
	public function testPasswordConfirmation(){
		$this->browse(function (Browser $browser) {
			$validationArray = $this->getValidationMessagesArray( 'register' );

			$password = Str::random( 7 );
			$password_confirmation = Str::random( 8 );

			$browser->visit( route( 'event-planner.register.show' ) )
				->type( 'password', $password )
				->type( 'password_confirmation', $password_confirmation )
				->assertSee( $validationArray[ 'password' ][ 'confirmed' ] )
				->press( 'Register' )
				->assertSee( $validationArray[ 'password' ][ 'confirmed' ] );
			$this->assertNull(User::first() );
		});
	}

    /**
     * Test the rules for checking the password characters
     * @group register-passwordchars
     * @return void
     * @throws Throwable
     */
	public function testPasswordCharacters(){
		$this->browse(function (Browser $browser) {
			$validationArray = $this->getValidationMessagesArray( 'register' );

			$password = 'password';

			$browser->visit( route( 'event-planner.register.show' ) )
			->type( 'password', $password )
			->type( 'password_confirmation', $password )
			->assertSee( $validationArray[ 'password' ][ 'regex' ] )
			->press( 'Register' )
			->assertSee( $validationArray[ 'password' ][ 'regex' ] );
			$this->assertNull(User::first() );
		});
	}

    /**
     * Test a user's successful registration
     * @return void
     * @throws Throwable
     */
	public function testSuccessfulRegistration(){
		/*
		 * Visit the Registration page and confirm a user can successfully create an account
		 */
		$this->browse(function ( Browser $browser ) {
			/*
			 * Create a user for registration
			 */
			$user = User::factory()->clearpass()->make();

            $clearpass = $user->clearpass;
			$browser->visit( route( 'event-planner.register.show' ) )
				->assertSee( 'E-Mail Address' )
				->type( 'name', $user->name )
				->type( 'email', $user->email )
				->type( 'password', $clearpass )
				->type( 'password_confirmation', $clearpass )
				->press( 'Register' )
				->assertSee( "Hello, $user->name!" );
			$this->assertFalse(User::where('name', $user->name )->get()->isEmpty() );
		});

	}
}
