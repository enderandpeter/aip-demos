<?php

namespace Tests\Feature\EventPlanner;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Carbon\Carbon;

use App\Models\EventPlanner\CalendarEvent;
use App\Models\EventPlanner\User;

class EditCalendarEventTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test field validation of input length
     *
     * @group edit-fieldlengths-feature
     * @group edit-feature
     * @return void
     */
	public function testFieldLengths()
    {
    	$caldendarEvent =  CalendarEvent::factory()->create();
    	$user = User::find($caldendarEvent->user_id );

    	$name = Str::random( 256 );
    	$type = Str::random( 192 );
    	$host = Str::random( 192 );
    	$guest_list = Str::random( 1001 );
    	$location = Str::random( 192 );
    	$guest_message = Str::random( 5001 );

    	/*
    	 * Confirm the error session keys when explicitly posting the registration form
    	 */
    	$this->get( route( 'event-planner.events.edit', $caldendarEvent->id ) );
    	$this->actingAs( $user )
    	->put( route( 'event-planner.events.update', $caldendarEvent->id ), [
    			'name' => $name,
    			'type' => $type,
    			'host' => $host,
    			'guest_list' => $guest_list,
    			'location' => $location,
    			'guest_message' => $guest_message,
    			'_token' => csrf_token()
    	])
    	->assertRedirect( route( 'event-planner.events.edit', $caldendarEvent->id ) )
    	->assertSessionHasErrors( [ 'name', 'type', 'host', 'guest_list', 'location', 'start_date', 'end_date', 'guest_message' ] );
    }

    /**
     * Test required fields
     *
     * @group edit-required-feature
     * @group edit-feature
     */
    public function testRequiredField()
    {
    	$caldendarEvent = CalendarEvent::factory()->create();
    	$user = User::find($caldendarEvent->user_id );

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
    	$this->get( route( 'event-planner.events.edit', $caldendarEvent->id ) );
    	$this->actingAs( $user )
    	->put( route( 'event-planner.events.update', $caldendarEvent->id ), [
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
    	->assertRedirect( route( 'event-planner.events.edit', $caldendarEvent->id ) )
    	->assertSessionHasErrors( [ 'name', 'type', 'host', 'guest_list', 'location', 'start_date', 'end_date' ] );
    }

    /**
     * Test date field errors
     *
     * @group edit-datefields-feature
     * @group edit-feature
     */
    public function testDateFields()
    {
    	$caldendarEvent = CalendarEvent::factory()->create();
    	$user = User::find($caldendarEvent->user_id );

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
    	$this->get( route( 'event-planner.events.edit', $caldendarEvent->id ) );
    	$this->actingAs( $user )
    	->put( route( 'event-planner.events.update', $caldendarEvent->id ), [
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
    	->assertRedirect( route( 'event-planner.events.edit', $caldendarEvent->id ) )
    	->assertSessionHasErrors( [ 'start_date', 'end_date' ] );
    }

    /**
     * Test successful update
     *
     * @group edit-successful-feature
     * @group edit-feature
     */
    public function testSuccessfulUpdate()
    {
    	$caldendarEvent = CalendarEvent::factory()->create();
    	$user = User::find($caldendarEvent->user_id );

    	$name = Str::random( 20 );
    	$type = Str::random( 30 );
    	$host = Str::random( 40 );
    	$guest_list = Str::random( 500 );
    	$location = Str::random( 20 );
    	$guest_message = Str::random( 300 );

    	$date = $caldendarEvent->start_date;
    	$start_date = clone $date;
    	$start_date->subHour( 1 );

    	$end_date = clone $date;
    	$end_date->addHour( 1 );
    	$date_format = CalendarEvent::$date_format;

    	/*
    	 * Confirm the error session keys when explicitly posting the registration form
    	 */
    	$this->get( route( 'event-planner.events.edit', $caldendarEvent->id ) );
    	$this->actingAs( $user )
    	->put( route( 'event-planner.events.update', $caldendarEvent->id ), [
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
    	->assertRedirect( route( 'event-planner.events.show', $caldendarEvent->id) );

    	$this->get( route( 'event-planner.events.show', $caldendarEvent->id) )
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
