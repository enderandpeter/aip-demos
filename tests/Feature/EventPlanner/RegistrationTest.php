<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\EventPlanner\User as User;

/**
 * Confirm basic endpoints and behavior for Event Planner user registration
 * 
 * @author Spencer
 *
 */
class RegistrationTest extends TestCase
{
	use DatabaseMigrations;
	
	/**
	 * A basic test example.
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
		->assertSessionHasErrors( ['name', 'email', 'password'] );
		$this->assertNull( User::first() );
	}
	
	/**
	 * Test the maximum length restraints for the registration form
	 *
	 * @return void
	 */
	public function testFieldLengths(){
		$clearpass = str_random( 256 );
		$shortclearpass = str_random( 3 );
		$name = str_random( 256 );
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => str_random( 256 ) . '@example.com',
				'password' => $clearpass,
				'password_confirmation' => $clearpass,
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( ['name', 'email', 'password'] );		
		$this->assertTrue( User::where( 'name', $name )->get()->isEmpty() );
		
		$clearpass = str_random( 3 );
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => str_random( 256 ) . '@example.com',
				'password' => $clearpass,
				'password_confirmation' => $clearpass,
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( ['name', 'email', 'password'] );
		$this->assertTrue( User::where( 'name', $name )->get()->isEmpty() );
	}
	
	/**
	 * Test the rules for validating the password
	 * 
	 * @return void
	 */
	public function testPasswordValidation(){
		$password = str_random( 7 );
		$password_confirmation = str_random( 8 );
		
		/*
		 * Confirm the error session keys when explicitly posting the registration form
		 */
		$name = str_random( 10 );
		$this->get( route( 'event-planner.register.show' ) );
		$this->post( route( 'event-planner.register.post' ), [
				'name' => $name,
				'email' => str_random( 256 ) . '@example.com',
				'password' => $password,
				'password_confirmation' => $password_confirmation,
				'_token' => csrf_token()
		])
		->assertRedirect( route( 'event-planner.register.show' ) )
		->assertSessionHasErrors( ['password'] );			
		$this->assertTrue( User::where( 'name', $name )->get()->isEmpty() );
	}
}