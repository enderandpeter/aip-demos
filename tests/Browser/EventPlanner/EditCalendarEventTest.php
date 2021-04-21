<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Models\EventPlanner\User;
use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;
use App\Models\EventPlanner\CalendarEvent;
use Throwable;

class EditCalendarEventTest extends DuskTestCase
{
	use DatabaseMigrations, ValidatesEventPlannerRequests;

    /**
     * Check that the form for editing an event displays and operates correctly.
     *
     * @group edit-calendarevent
     * @group loginas
     * @return void
     * @throws Throwable
     */
	public function testRequiredFields()
	{
		$this->browse( function ( Browser $browser ) {
			$calendarEvent = CalendarEvent::factory()->create();
			$user = User::find($calendarEvent->user_id );

			$calendarHeading = $calendarEvent->start_date->toFormattedDateString();

			$validationArray = $this->getValidationMessagesArray( 'create-event' );

			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $calendarEvent->id ) )
			->assertSee( $calendarHeading )
			->clear( 'name' )
            ->assertSee( $validationArray[ 'name' ][ 'required' ] )
			->clear( 'type' )
            ->assertSee( $validationArray[ 'type' ][ 'required' ] )
			->clear( 'host' )
            ->assertSee( $validationArray[ 'host' ][ 'required' ] )
			->clear( 'start_date' )
			->press( 'Done' )
            ->assertSee( $validationArray[ 'start_date' ][ 'required' ] )
			->clear( 'end_date' )
            ->assertSee( $validationArray[ 'end_date' ][ 'required' ] )
			->press( 'Done' )
			->clear( 'guest_list' )
            ->assertSee( $validationArray[ 'guest_list' ][ 'required' ] )
			->clear( 'location' )
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
