<?php

namespace Tests\Feature\EventPlanner;

use Tests\TestCase;
use Carbon\Carbon;

use App\EventPlanner\CalendarEvent;
use App\EventPlanner\User as User;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeleteCalendarEventTest extends TestCase
{
	use DatabaseMigrations;
	
    /**
     * Test successful CalendarEvent deletion
     *
     * @group delete-success-feature     
     * @group delete-feature
     * @return void
     */
	public function testSuccessfulDeletion()
    {
    	$caldendarEvent = factory( CalendarEvent::class )->create();
    	$user = User::find( $caldendarEvent->user_id );    	
    	
    	$this->get( route( 'event-planner.events.edit', $caldendarEvent->id ) );
    	$this->actingAs( $user )
    	->delete( route( 'event-planner.events.destroy', $caldendarEvent->id ) )
    	->assertRedirect( route( 'event-planner' ) );
    	$this->assertNull( CalendarEvent::find( $caldendarEvent->id ) );
    }    
}
