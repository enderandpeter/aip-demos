<?php

namespace Tests\Feature\EventPlanner;

use App\Models\EventPlanner\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\EventPlanner\CalendarEvent;


class IndexCalendarEventTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test that all of a user's calendar events are indexed
     *
     * @group index-events-feature
     * @group index-feature
     * @return void
     */
	public function testIndexEvents()
    {
    	$caldendarEvents = CalendarEvent::factory()->count(5)->create();
    	$user = User::find($caldendarEvents[0]->user_id );

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
