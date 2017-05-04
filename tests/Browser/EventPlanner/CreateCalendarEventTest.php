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
     * @group create-today
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
    
    /**
     * Test to make sure user is warned if date input is out of order.
     * 
     * @group create-datefields
     * @return void
     */
    public function testDateFields(){
    	$this->browse( function ( Browser $browser ) {
	    	$user = factory( User::class )->create();
	    	$date = Carbon::now();
	    	
	    	$calendarHeading = $date->toFormattedDateString();
	    	
	    	$start_date = clone $date;	    	
	    	$start_date->hour( 12 )->minute( 0 );
	    	
	    	$end_date = clone $date;
	    	$end_date->hour( 11 )->minute( 0 );
	    	$date_format = 'm/d/y H:i';
	    	
	    	$validationArray = $this->getValidationMessagesArray( 'create-event' );
	    	
	    	$browser->loginAs( $user, 'eventplanner' )
	    		->visit( route( 'event-planner.events.create' ) )
	    		->type( 'start_date', $start_date->format( $date_format ) )
	    		->type( 'end_date', $end_date->format( $date_format ) )
	    		->click( '.container' )
	    		->assertSee( str_replace( ":date", "start_date", $validationArray[ 'end_date' ][ 'after_or_equal' ] ) )
	    		->click( '#start_date' )
	    		->click( '.container' )
	    		->assertSee( str_replace( ":date", "end_date", $validationArray[ 'start_date' ][ 'before_or_equal' ] ) );	    		
    	});
    }
}
