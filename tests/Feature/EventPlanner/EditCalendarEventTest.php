<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;
use Carbon\Carbon;

use App\EventPlanner\CalendarEvent;
use App\EventPlanner\EventPlannerUser as User;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditCalendarEventTest extends TestCase
{
	use DatabaseMigrations;

    /**
     * Test field validation of input length
     *
     * @group edit-fieldlengths-feature
     * @group edit-feature
     * @return void
     */
	public function testFieldLengths()
    {
    	$caldendarEvent = factory( CalendarEvent::class )->create();
    	$user = EventPlannerUser::find($caldendarEvent->user_id );

    	$name = str_random( 256 );
    	$type = str_random( 192 );
    	$host = str_random( 192 );
    	$guest_list = str_random( 1001 );
    	$location = str_random( 192 );
    	$guest_message = str_random( 5001 );

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
    	$caldendarEvent = factory( CalendarEvent::class )->create();
    	$user = EventPlannerUser::find($caldendarEvent->user_id );

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
    	$caldendarEvent = factory( CalendarEvent::class )->create();
    	$user = EventPlannerUser::find($caldendarEvent->user_id );

    	$name = str_random( 20 );
    	$type = str_random( 30 );
    	$host = str_random( 40 );
    	$guest_list = str_random( 500 );
    	$location = str_random( 20 );
    	$guest_message = str_random( 300 );

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
    	$caldendarEvent = factory( CalendarEvent::class )->create();
    	$user = EventPlannerUser::find($caldendarEvent->user_id );

    	$name = str_random( 20 );
    	$type = str_random( 30 );
    	$host = str_random( 40 );
    	$guest_list = str_random( 500 );
    	$location = str_random( 20 );
    	$guest_message = str_random( 300 );

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
