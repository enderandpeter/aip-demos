<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;

use App\EventPlanner\User as User;
use App\EventPlanner\CalendarEvent;

class DeleteCalendarEventTest extends DuskTestCase
{
	use DatabaseMigrations;
   
	/**
	 * Confirm that a calendar event can be deleted
	 *
	 * @group delete-success
	 * @group loginas
	 * @return void
	 */
	public function testSuccessfulDeletion()
	{
		$this->browse( function ( Browser $browser ) {
			$caldendarEvent = factory( CalendarEvent::class )->create();
			$user = User::find( $caldendarEvent->user_id );
			
			$calendarHeading = $caldendarEvent->start_date->toFormattedDateString();
			
			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id ) )
			->assertSee( $calendarHeading )
			->press( 'Delete' )
			->press( 'Delete Event' )
			->assertRouteIs( 'event-planner' )
			->assertSee( 'Done!' );
			
			$this->assertNull( CalendarEvent::find( $caldendarEvent->id ), 'The deleted calendar event still exists.' );
		});	
	}
	
	/**
	 * Confirm that the user can cancel the deletion of a calendar event
	 *
	 * @group delete-cancel
	 * @group loginas
	 * @return void
	 */
	public function testCanceledDeletion()
	{
		$this->browse( function ( Browser $browser ) {
			$caldendarEvent = factory( CalendarEvent::class )->create();
			$user = User::find( $caldendarEvent->user_id );
			
			$calendarHeading = $caldendarEvent->start_date->toFormattedDateString();
			
			$browser->loginAs( $user, 'eventplanner' )
			->visit( route( 'event-planner.events.edit', $caldendarEvent->id ) )
			->assertSee( $calendarHeading )
			->press( 'Delete' )
			->press( 'Cancel' )
			->assertRouteIs( 'event-planner.events.edit', $caldendarEvent->id );
			
			$this->assertNotNull( CalendarEvent::find( $caldendarEvent->id ), 'The calendar event was deleted.' );
		});
	}

}
