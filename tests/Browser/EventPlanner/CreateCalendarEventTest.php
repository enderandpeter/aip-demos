<?php

namespace Tests\Browser\EventPlanner;

use Illuminate\Support\Str;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;

use App\Models\EventPlanner\User;
use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;
use App\Models\EventPlanner\CalendarEvent;
use Throwable;

class CreateCalendarEventTest extends DuskTestCase
{
	use DatabaseMigrations, ValidatesEventPlannerRequests;

    /**
     * Check that the form for creating an event displays and operates correctly.
     *
     * @group create-today
     * @group loginas
     * @return void
     * @throws Throwable
     */
    public function testRequiredFields()
    {
        $this->browse( function ( Browser $browser ) {
        	$user = User::factory()->create();
        	$date = Carbon::now();

        	$calendarHeading = $date->toFormattedDateString();

        	$validationArray = $this->getValidationMessagesArray( 'create-event' );

            $browser->loginAs( $user, 'eventplanner' )
            	->visit( route( 'event-planner.events.create' ) )
            	->assertSee( $calendarHeading )
            	->type( 'name', '' )
            	->type( 'type', '' )
            	->assertSee( $validationArray[ 'name' ][ 'required' ] )
            	->type( 'host', '' )
            	->assertSee( $validationArray[ 'type' ][ 'required' ] )
            	->type( 'start_date', '' )
            	->click( '.ui-datepicker-close' )
            	->assertSee( $validationArray[ 'host' ][ 'required' ] )
            	->type( 'end_date', '' )
            	->click( '.ui-datepicker-close' )
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
     * Test to make sure user is warned if date input is out of order.
     *
     * @group create-startdatefield
     * @group loginas
     * @return void
     * @throws Throwable
     */
    public function testStartDateField(){
    	$this->browse( function ( Browser $browser ) {
	    	$date = Carbon::now();
	    	$user = User::factory()->create();

	    	$calendarHeading = $date->toFormattedDateString();

	    	$start_date = clone $date;
	    	$start_date->hour( 12 )->minute( 0 );

	    	$end_date = clone $date;
	    	$end_date->hour( 11 )->minute( 0 );
	    	$date_format = CalendarEvent::$date_format;

	    	$validationArray = $this->getValidationMessagesArray( 'create-event' );

	    	$browser->loginAs( $user, 'eventplanner' )
	    		->visit( route( 'event-planner.events.create' ) )
	    		->type( 'end_date', $end_date->format( $date_format ) )
	    		->type( 'start_date', $start_date->format( $date_format ) )
	    		->assertSee( str_replace( ":date", "end_date", $validationArray[ 'start_date' ][ 'before_or_equal' ] ) );
    	});
    }

    /**
     * Test to make sure user is warned if date input is out of order.
     *
     * @group create-enddatefield
     * @group loginas
     * @return void
     * @throws Throwable
     */
    public function testEndDateField(){
    	$this->browse( function ( Browser $browser ) {
    		$date = Carbon::now();
    		$user = User::factory()->create();

    		$calendarHeading = $date->toFormattedDateString();

    		$start_date = clone $date;
    		$start_date->hour( 12 )->minute( 0 );

    		$end_date = clone $date;
    		$end_date->hour( 11 )->minute( 0 );
    		$date_format = CalendarEvent::$date_format;

    		$validationArray = $this->getValidationMessagesArray( 'create-event' );

    		$browser->loginAs( $user, 'eventplanner' )
    		->visit( route( 'event-planner.events.create' ) )
    		->type( 'start_date', $start_date->format( $date_format ) )
    		->type( 'end_date', $end_date->format( $date_format ) )
    		->assertSee( str_replace( ":date", "start_date", $validationArray[ 'end_date' ][ 'after_or_equal' ] ) );
    	});
    }

    /**
     * Test the successful creation of a calendar event
     *
     * @group create-success
     * @group loginas
     * @return void
     * @throws Throwable
     */
    public function testSuccessfulCreation(){
    	$this->browse( function ( Browser $browser ) {
    		$date = Carbon::now();
    		$user = User::factory()->create();

    		$calendarHeading = $date->toFormattedDateString();

    		$name = Str::random( 20 );
    		$type = Str::random( 30 );
    		$host = Str::random( 40 );
    		$guest_list = Str::random( 500 );
    		$location = Str::random( 20 );
    		$guest_message = Str::random( 300 );

    		$start_date = clone $date;
    		$start_date->hour( 11 )->minute( 0 );

    		$end_date = clone $date;
    		$end_date->hour( 12 )->minute( 0 );

    		$date_format = CalendarEvent::$date_format;

    		$browser->loginAs( $user, 'eventplanner' )
	    		->visit( route( 'event-planner.events.create' ) )
	    		->type( 'name', $name )
	    		->type( 'type', $type)
	    		->type( 'host', $host)
	    		->type( 'start_date', $start_date->format( $date_format ) )
	    		->click( '.ui-datepicker-close' )
	    		->type( 'end_date', $end_date->format( $date_format ) )
	    		->click( '.ui-datepicker-close' )
	    		->type( 'guest_list', $guest_list )
	    		->type( 'location', $location)
	    		->type( 'guest_message', $guest_message )
	    		->press( 'Create' )
	    		->assertRouteIs( 'event-planner.events.show', CalendarEvent::first()->id )
	    		->assertSee( 'Well done!' )
    			->assertSeeIn( '#name', $name )
    			->assertSeeIn( '#type', $type)
    			->assertSeeIn( '#host', $host)
    			->assertSeeIn( '#start_date', $start_date->format( $date_format) )
    			->assertSeeIn( '#end_date', $end_date->format( $date_format) )
    			->assertSeeIn( '#guest_list', $guest_list )
    			->assertSeeIn( '#location', $location)
    			->assertSeeIn( '#guest_message', $guest_message );
    	});
    }
}
