<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;
use Carbon\Carbon;

use App\EventPlanner\CalendarEvent;
use App\EventPlanner\EventPlannerUser as User;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomeCalendarEventTest extends TestCase
{
	use DatabaseMigrations;

    /**
     * Check that a user's calendar events are shown on the right calendar on the home page
     *
     * @group index-show-feature
     * @group home-feature
     * @return void
     */
	public function testIndexEvents()
    {
    	$caldendarEvents = factory( CalendarEvent::class, 5 )->create();
    	$user = EventPlannerUser::find($caldendarEvents[0]->user_id );

		/*
		 * Confirm the visibility of all of a user's calendar events.
		 */
    	$response = $this->actingAs( $user );
    	foreach( $caldendarEvents as $calendarEvent ){
    		$response->get( route( 'event-planner', [
    				'year' => $calendarEvent->start_date->year,
    				'month' => $calendarEvent->start_date->month,
    				'day' => $calendarEvent->start_date->day,
    				'submit' => 1
    		] ) )
    		->assertSee( $calendarEvent->name )
    		->assertSee( $calendarEvent->getStartTime() );
    	}

    }
}
