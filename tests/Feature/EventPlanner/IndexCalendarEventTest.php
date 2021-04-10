<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;
use Carbon\Carbon;

use App\EventPlanner\CalendarEvent;
use App\EventPlanner\EventPlannerUser as User;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class IndexCalendarEventTest extends TestCase
{
	use DatabaseMigrations;

    /**
     * Test that all of a user's calendar events are indexed
     *
     * @group index-events-feature
     * @group index-feature
     * @return void
     */
	public function testIndexEvents()
    {
    	$caldendarEvents = factory( CalendarEvent::class, 5 )->create();
    	$user = EventPlannerUser::find($caldendarEvents[0]->user_id );

		/*
		 * Confirm the visibility of all of a user's calendar events.
		 */
    	$response = $this->actingAs( $user )
    	->get( route( 'event-planner.events.index' ) );
    	foreach( $caldendarEvents as $calendarEvent ){
    		$response->assertSee( $calendarEvent->location )
    		->assertSee( $calendarEvent->type )
    		->assertSee( $calendarEvent->showStartDate() );
    	}

    }
}
