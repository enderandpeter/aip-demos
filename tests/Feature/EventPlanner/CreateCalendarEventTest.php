<?php

namespace Tests\Feature\EventPlanner;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Carbon\Carbon;

use App\Models\EventPlanner\CalendarEvent;
use App\Models\EventPlanner\User;

class CreateCalendarEventTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test field validation of input length
     *
     * @group create-fieldlengths-feature
     * @group create-feature
     * @return void
     */
	public function testFieldLengths()
    {
    	$user = User::factory()->create();

    	$name = Str::random( 256 );
    	$type = Str::random( 192 );
    	$host = Str::random( 192 );
    	$guest_list = Str::random( 1001 );
    	$location = Str::random( 192 );
    	$guest_message = Str::random( 5001 );

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
    	$user = User::factory()->create();

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
     * Test date field errors
     *
     * @group create-datefields-feature
     * @group create-feature
     */
    public function testDateFields()
    {
    	$user = User::factory()->create();

    	$name = Str::random( 20 );
    	$type = Str::random( 30 );
    	$host = Str::random( 40 );
    	$guest_list = Str::random( 500 );
    	$location = Str::random( 20 );
    	$guest_message = Str::random( 300 );

    	$date = Carbon::now();
    	$start_date = clone $date;
    	$start_date->hour( 12 )->minute( 0 );

    	$end_date = clone $date;
    	$end_date->hour( 11 )->minute( 0 );
    	$date_format = CalendarEvent::$date_format;

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
    	->assertSessionHasErrors( [ 'start_date', 'end_date' ] );
    	$this->assertNull( CalendarEvent::first() );
    }

    /**
     * Test successful creation
     *
     * @group create-successful-feature
     * @group create-feature
     */
    public function testSuccessfulCreation()
    {
    	$user = User::factory()->create();

    	$name = Str::random( 20 );
    	$type = Str::random( 30 );
    	$host = Str::random( 40 );
    	$guest_list = Str::random( 500 );
    	$location = Str::random( 20 );
    	$guest_message = Str::random( 300 );

    	$date = Carbon::now();
    	$start_date = clone $date;
    	$start_date->hour( 11 )->minute( 0 );

    	$end_date = clone $date;
    	$end_date->hour( 12 )->minute( 0 );
    	$date_format = CalendarEvent::$date_format;

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
    	->assertSee( $start_date->format( $date_format ) )
    	->assertSee( $end_date->format( $date_format ) )
    	->assertSee( $guest_list )
    	->assertSee( $location)
    	->assertSee( $guest_message );
    }
}
