<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;

use App\EventPlanner\CalendarEvent;
use App\EventPlanner\User as User;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateCalendarEventTest extends TestCase
{
	use DatabaseMigrations;
	
    /**
     * Test field validation of input length
     *
     * @group create-fieldlengths-feature     
     * @group create-feature
     * @return void
     */
	public function testFieldLengths()
    {
    	$user = factory( User::class )->create();
    	
    	$name = str_random( 256 );
    	$type = str_random( 192 );
    	$host = str_random( 192 );
    	$guest_list = str_random( 1001 );
    	$location = str_random( 192 );
    	$guest_message = str_random( 5001 );
    	
    	/*
    	 * Confirm the error session keys when explicitly posting the registration form
    	 */
    	$this->get( route( 'event-planner.events.create' ) );
    	$this->actingAs( $user )
    	->post( route( 'event-planner.events.store' ), [
    			'name' => $name,
    			'type' => $type,
    			'host' => $host,
    			'guest_list' => $guest_list,
    			'location' => $location,
    			'guest_message' => $guest_message,
    			'_token' => csrf_token()
    	])
    	->assertRedirect( route( 'event-planner.events.create' ) )
    	->assertSessionHasErrors( [ 'name', 'type', 'host', 'guest_list', 'location', 'start_date', 'end_date', 'guest_message' ] );
    	$this->assertNull( CalendarEvent::first() );
    }
    
    /**
     * Test required fields
     * 
     * @group create-required-feature
     * @group create-feature
     */
    public function testRequiredField()
    {
    	$user = factory( User::class )->create();
    	
    	$name = '';
    	$type = '';
    	$host = '';
    	$guest_list = '';
    	$location = '';
    	$start_date = '';
    	$end_date = '';
    	$guest_message = '';
    	
    	/*
    	 * Confirm the error session keys when explicitly posting the registration form
    	 */
    	$this->get( route( 'event-planner.events.create' ) );
    	$this->actingAs( $user )
    	->post( route( 'event-planner.events.store' ), [
    			'name' => $name,
    			'type' => $type,
    			'host' => $host,
    			'start_date' => $start_date,
    			'end_date' => $end_date,
    			'guest_list' => $guest_list,
    			'location' => $location,
    			'guest_message' => $guest_message,
    			'_token' => csrf_token()
    	])
    	->assertRedirect( route( 'event-planner.events.create' ) )
    	->assertSessionHasErrors( [ 'name', 'type', 'host', 'guest_list', 'location', 'start_date', 'end_date' ] );
    	$this->assertNull( CalendarEvent::first() );
    }
}
