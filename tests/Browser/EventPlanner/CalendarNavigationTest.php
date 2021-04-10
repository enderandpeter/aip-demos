<?php

namespace Tests\Browser\EventPlanner;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Carbon\Carbon;
use Faker\Factory as Faker;

use App\EventPlanner\EventPlannerUser as User;

class CalendarNavigationTest extends DuskTestCase
{
	use DatabaseMigrations;

	/**
     * Test to confirm that current date appears on default event page
     *
     * @group currentdate
     * @group loginas
     * @return void
     */
    public function testCurrentDate()
    {
    	$user = factory(EventPlannerUser::class )->create();

    	$this->browse(function ( Browser $browser ) use ( $user ) {
    		$date = Carbon::now();

    		$dateHeaderFormat = 'F Y';

    		$headerID = 'calendarHeading';
    		$headerText = $date->format( $dateHeaderFormat );

    		/*
    		 * When logged in and visiting /event-planner, the current date should be in the calendar heading
    		 * with the proper format
    		 */

    		$browser->loginAs( $user, 'eventplanner' )
    			->visit( route( 'event-planner' ) )
    			->assertSeeIn( '#calendarHeading', $headerText ) // Header text should be present
    			->assertSelected( '#month', $date->format( 'n' ) ) // Header should have current date
    			->assertValue( '#year', $date->format( 'Y' ) )
    			->assertSeeIn( '#today', $date->format( 'j' ) ); // The current day should be in the appropriate element
    	});
    }

    /**
     * Test to make sure the date entered into the form is loaded
     *
     * @group gotodate
     * @group loginas
     * @return void
     */
    public function testGoToDate(){
    	$user = factory(EventPlannerUser::class )->create();

    	$this->browse( function ( Browser $browser ) use ( $user ) {
    		$faker = Faker::create();

    		$date = Carbon::instance( $faker->dateTime() );

    		$dateHeaderFormat = 'F Y';

    		$browser->loginAs( $user, 'eventplanner' )
	    		->visit( route( 'event-planner' ) )
	    		->select( '#month', (string) $date->month )
	    		->type( '#year', $date->year )
	    		->press( 'Go' )
	    		->assertSee( $date->format( $dateHeaderFormat) );
    	});
    }

    /**
     * Test to make sure the current date will load when the button is clicked
     * @group gotocurrent
     * @group loginas
     * @return void
     */
    public function testGoToCurrentDate(){

    	$user = factory(EventPlannerUser::class )->create();

    	$this->browse( function ( Browser $browser ) use ( $user ) {
    		$date = Carbon::now();

    		$dateHeaderFormat = 'F Y';

    		$browser->loginAs( $user, 'eventplanner' )
	    		->visit( route( 'event-planner' ) )
	    		->press( 'Today' )
	    		->assertSee( $date->format( $dateHeaderFormat ) );
    	});
    }
}
