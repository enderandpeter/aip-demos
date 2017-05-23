<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;
use Carbon\Carbon;

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
    
    /**
     * Test required fields
     *
     * @group create-successful-feature
     * @group create-feature
     */
    public function testSuccessfulCreation()
    {
    	$user = factory( User::class )->create();
    	
    	$name = str_random( 20 );
    	$type = str_random( 30 );
    	$host = str_random( 40 );
    	$guest_list = str_random( 500 );
    	$location = str_random( 20 );
    	$guest_message = str_random( 300 );
    	
    	$date = Carbon::now();
    	$start_date = clone $date;
    	$start_date->hour( 11 )->minute( 0 );
    	
    	$end_date = clone $date;
    	$end_date->hour( 12 )->minute( 0 );
    	$date_format = 'm/d/y H:i';
    	
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
    	->assertRedirect( route( 'event-planner.events.show', CalendarEvent::first()->id ) );
    	
    	$this->get( route( 'event-planner.events.show', CalendarEvent::first()->id ) )
    	->assertSee( $name )
    	->assertSee( $type)
    	->assertSee( $host)
    	->assertSee( $start_date->format( CalendarEvent::$show_date_format ) )
    	->assertSee( $end_date->format( CalendarEvent::$show_date_format ) )
    	->assertSee( $guest_list )
    	->assertSee( $location)
    	->assertSee( $guest_message );
    }
}
