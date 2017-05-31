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
	
	/*
	 * We'll have to forgo tests for editing the date fields and testing successful submission until Dusk stops being
	 * so unpredictable. When Carbon/datetime values are entered by Dusk, sometimes they are the original database values
	 * for the field and sometimes they are the ones explicitly assigned in the test. It seems to randomly decide
	 * when to follow test instructions or do its own thing.
	 */
}
