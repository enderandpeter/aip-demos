<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;

use App\EventPlanner\User as User;
use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;

class CreateCalendarEventTest extends DuskTestCase
{
	use DatabaseMigrations, ValidatesEventPlannerRequests;
	
    /**
     * Check that the form for creating an event displays and operates correctly.
     *
     * @group createtoday
     * @group loginas
     * @return void
     */
    public function testRequiredFields()
    {    	
        $this->browse( function ( Browser $browser ) {
        	$user = factory( User::class )->create();
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
}
