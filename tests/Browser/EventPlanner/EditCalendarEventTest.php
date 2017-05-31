<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;

use App\EventPlanner\User as User;
use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;
use App\EventPlanner\CalendarEvent;

class EditCalendarEventTest extends DuskTestCase
{
	use DatabaseMigrations, ValidatesEventPlannerRequests;
   
	/**
	 * Check that the form for editing an event displays and operates correctly.
	 *
	 * @group edit-calendarevent
	 * @group loginas
	 * @return void
	 */
	public function testRequiredFields()
	{
		$this->browse( function ( Browser $browser ) {
			$caldendarEvent = factory( CalendarEvent::class )->create();
			$user = User::find( $caldendarEvent->user_id );
			
			$calendarHeading = $caldendarEvent->start_date->toFormattedDateString();
			
			$validationArray = $this->getValidationMessagesArray( 'create-event' );
			
			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id ) )
			->assertSee( $calendarHeading )
			->type( 'name', '' )
			->type( 'type', '' )
			->assertSee( $validationArray[ 'name' ][ 'required' ] )
			->type( 'host', '' )
			->assertSee( $validationArray[ 'type' ][ 'required' ] )
			->type( 'start_date', '' )
			->assertSee( $validationArray[ 'host' ][ 'required' ] )
			->type( 'end_date', '' )
			->assertSee( $validationArray[ 'start_date' ][ 'required' ] )
			->type( 'guest_list', '' )
			->assertSee( $validationArray[ 'end_date' ][ 'required' ] )
			->type( 'location', '' )
			->assertSee( $validationArray[ 'guest_list' ][ 'required' ] )
			->type( 'name', '' )
			->assertSee( $validationArray[ 'location' ][ 'required' ] );
		});
	}
	
	/**
	 * Test to make sure user is warned if start_date is not before start_date
	 *
	 * @group edit-startdatefield
	 * @group loginas
	 * @return void
	 */
	public function testStartDateFields(){
		$this->browse( function ( Browser $browser ) {
			$caldendarEvent = factory( CalendarEvent::class )->create();
			$user = User::find( $caldendarEvent->user_id );
			
			$date = clone $caldendarEvent->end_date;
			
			$start_date = $date->addHour();
			
			$calendarHeading = $start_date->toFormattedDateString();
			
			$date_format = CalendarEvent::$date_format;
			
			$validationArray = $this->getValidationMessagesArray( 'create-event' );
			
			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id ) )
			->type( 'start_date', $start_date->format( $date_format ) )
			->assertSee( str_replace( ":date", "end_date", $validationArray[ 'start_date' ][ 'before_or_equal' ] ) );
		});
	}
	
	/**
	 * Test to make sure user is warned if end_date is before start_date
	 *
	 * @group edit-enddatefield
	 * @group loginas
	 * @return void
	 */
	public function testEndDateFields(){
		$this->browse( function ( Browser $browser ) {
			$caldendarEvent = factory( CalendarEvent::class )->create();
			$user = User::find( $caldendarEvent->user_id );			
			
			$start_date = $caldendarEvent->start_date;
			$end_date = $caldendarEvent->start_date->subHour();
			
			$calendarHeading = $start_date->toFormattedDateString();
			
			$date_format = CalendarEvent::$date_format;
			
			$validationArray = $this->getValidationMessagesArray( 'create-event' );
			
			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id ) )
			->type( 'end_date', $end_date->format( $date_format ) )
			->assertSee( str_replace( ":date", "start_date", $validationArray[ 'end_date' ][ 'after_or_equal' ] ) );
		});
	}
	
	/**
	 * Test the successful update of a calendar event
	 *
	 * @group edit-success
	 * @group loginas
	 * @return void
	 */
	public function testSuccessfulUpdate(){
		$this->browse( function ( Browser $browser ) {
			$caldendarEvent = factory( CalendarEvent::class )->create();
			$user = User::find( $caldendarEvent->user_id );			
			
			$date = $caldendarEvent->start_date; 
			
			$calendarHeading = $date->toFormattedDateString();
			
			$name = str_random( 20 );
			$type = str_random( 30 );
			$host = str_random( 40 );
			$guest_list = str_random( 500 );
			$location = str_random( 20 );
			$guest_message = str_random( 300 );
			
			$start_date = clone $date;
			$start_date->hour( 11 )->minute( 0 );
			
			$end_date = clone $date;
			$end_date->hour( 12 )->minute( 0 );
			
			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id) )
			->type( 'name', $name )
			->type( 'type', $type)
			->type( 'host', $host)
			->type( 'start_date', $start_date->format( CalendarEvent::$date_format) )
			->click( '.ui-datepicker-close' )
			->type( 'end_date', $end_date->format( CalendarEvent::$date_format) )
			->click( '.ui-datepicker-close' )
			->type( 'guest_list', $guest_list )
			->type( 'location', $location)
			->type( 'guest_message', $guest_message )
			->press( 'Update' )
			->assertRouteIs( 'event-planner.events.show', CalendarEvent::first()->id )
			->assertSee( 'Well done!' )
			->assertSeeIn( '#name', $name )
			->assertSeeIn( '#type', $type)
			->assertSeeIn( '#host', $host)
			->assertSeeIn( '#start_date', $start_date->format( CalendarEvent::$date_format ) )
			->assertSeeIn( '#end_date', $end_date->format( CalendarEvent::$date_format ) )
			->assertSeeIn( '#guest_list', $guest_list )
			->assertSeeIn( '#location', $location)
			->assertSeeIn( '#guest_message', $guest_message );
		});
	}
}
