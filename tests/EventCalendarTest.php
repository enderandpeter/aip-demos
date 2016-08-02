<?php

use Carbon\Carbon;
use Faker\Factory as Faker;

class EventCalendarTest extends TestCase
{
    /**
     * Basic tests for the event calendar
     *
     * @return void
     */
    public function testCurrentDate()
    {
    	/*
    	 * When visiting /event-planner, the current date should be in the calendar heading with the proper format
    	 */
    	
    	$date = Carbon::now();
    	
    	$dateHeaderFormat = 'F Y';
    	
    	$headerID = 'calendarHeading';
    	$headerText = $date->format($dateHeaderFormat);
    	
        $this->visit('/event-planner')
             ->seeInElement('#calendarHeading', $headerText) // Header text should be present
        	 ->seeIsSelected('#month', $date->format('n')) // Header should have current date
        	 ->seeInField('#year', $date->format('Y'))
        	 ->seeInElement('#today', $date->format('j')); // The current day should be in the appropriate element
        
        	 $this->visit('/event-planner/events')
        	 ->seeInElement('#calendarHeading', $headerText) // Header text should be present
        	 ->seeIsSelected('#month', $date->format('n')) // Header should have current date
        	 ->seeInField('#year', $date->format('Y'))
        	 ->seeInElement('#today', $date->format('j')); // The current day should be in the appropriate element
    }
    
    /**
     * Test to make sure the date entered into the form is loaded
     */
    public function testGoToDate(){
    	$faker = Faker::create();
    	
    	$date = Carbon::instance($faker->dateTime());
    	$dateHeaderFormat = 'F Y';
    	
    	$this->visit('/event-planner')
    		  ->select($date->month, '#month')
    		 ->type($date->year, '#year')
    		 ->press('Go')
    		 ->see($date->format($dateHeaderFormat));
    	
    		 $this->visit('/event-planner/events')
    		 ->select($date->month, '#month')
    		 ->type($date->year, '#year')
    		 ->press('Go')
    		 ->see($date->format($dateHeaderFormat));
    }
    
    /**
     * Test to make sure the current date will load when the button is clicked
     */
    public function testGoToCurrentDate(){
    	$date = Carbon::now();
    	 
    	$dateHeaderFormat = 'F Y';
    	 
    	$this->visit('/event-planner')
    	->press('Today')
    	->see($date->format($dateHeaderFormat));
    	
    	$this->visit('/event-planner/events')
    	->press('Today')
    	->see($date->format($dateHeaderFormat));
    }
}
