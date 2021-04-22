<?php

namespace Tests\Feature\EventPlanner;

use App\Models\EventPlanner\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Confirm basic endpoints and behavior for Event Planner user registration
 *
 */
class RegistrationTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Test that certain registration form fields are required
	 *
	 * @return void
	 */
	public function testRequiredFields()
	{
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => '',
				'email' => '',
				'password' => '',
				'password_confirmation' => '',
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( [ 'name', 'email', 'password' ] );
		$this->assertNull(User::first() );
	}

	/**
	 * Test the maximum length restraints for the registration form
	 *
	 * @return void
	 */
	public function testFieldLengths(){
		$clearpass = Str::random( 256 );
		$shortclearpass = Str::random( 3 );
		$name = Str::random( 256 );
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => Str::random( 256 ) . '@example.com',
				'password' => $clearpass,
				'password_confirmation' => $clearpass,
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( [ 'name', 'email', 'password' ] );
		$this->assertTrue(User::where('name', $name )->get()->isEmpty() );

		$clearpass = Str::random( 3 );
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => Str::random( 256 ) . '@example.com',
				'password' => $clearpass,
				'password_confirmation' => $clearpass,
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( [ 'name', 'email', 'password' ] );
		$this->assertTrue(User::where('name', $name )->get()->isEmpty() );
	}

	/**
	 * Test the rules for validating the password
	 *
	 * @return void
	 */
	public function testPasswordValidation(){
		$password = Str::random( 7 );
		$password_confirmation = Str::random( 8 );

		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$name = Str::random( 10 );
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => Str::random( 256 ) . '@example.com',
				'password' => $password,
				'password_confirmation' => $password_confirmation,
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( [ 'password' ] );
		$this->assertTrue(User::where('name', $name )->get()->isEmpty() );
	}
}
