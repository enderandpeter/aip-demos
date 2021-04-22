<?php

namespace Tests\Feature\EventPlanner;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\EventPlanner\CalendarEvent;
use App\Models\EventPlanner\User;

class DeleteCalendarEventTest extends TestCase
{
	use RefreshDatabase;

    /**
     * Test successful CalendarEvent deletion
     *
     * @group delete-success-feature
     * @group delete-feature
     * @return void
     */
	public function testSuccessfulDeletion()
    {
    	$caldendarEvent = CalendarEvent::factory()->create();
    	$user = User::find($caldendarEvent->user_id );

    	$this->get( route( 'event-planner.events.edit', $caldendarEvent->id ) );
    	$this->actingAs( $user )
    	->delete( route( 'event-planner.events.destroy', $caldendarEvent->id ) )
    	->assertRedirect( route( 'event-planner' ) );
    	$this->assertNull( CalendarEvent::find( $caldendarEvent->id ) );
    }
}
