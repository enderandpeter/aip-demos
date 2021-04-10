<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;

use App\EventPlanner\EventPlannerUser as User;
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
			$user = EventPlannerUser::find($caldendarEvent->user_id );

			$calendarHeading = $caldendarEvent->start_date->toFormattedDateString();

			$validationArray = $this->getValidationMessagesArray( 'create-event' );

			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id ) )
			->assertSee( $calendarHeading )
			->clear( 'name' )
			->clear( 'type' )
			->assertSee( $validationArray[ 'name' ][ 'required' ] )
			->clear( 'host' )
			->assertSee( $validationArray[ 'type' ][ 'required' ] )
			->clear( 'start_date' )
			->press( 'Done' )
			->assertSee( $validationArray[ 'host' ][ 'required' ] )
			->clear( 'end_date' )
			->press( 'Done' )
			->assertSee( $validationArray[ 'start_date' ][ 'required' ] )
			->clear( 'guest_list' )
			->assertSee( $validationArray[ 'end_date' ][ 'required' ] )
			->clear( 'location' )
			->assertSee( $validationArray[ 'guest_list' ][ 'required' ] )
			->clear( 'name' )
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
