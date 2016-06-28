<?php

use Carbon\Carbon;

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
    	
    	$monthFormat = 'F Y';
    	
    	$headerID = 'calendarHeading';
    	$headerText = $date->format($monthFormat);
    	
        $this->visit('/event-planner')
             ->seeInElement('#calendarHeading', $headerText) // Header text should be present
        	 ->seeIsSelected('#month', $date->format('n')) // Header should have current date
        	 ->seeInField('#year', $date->format('Y'))
        	 ->seeInElement('#today', $date->format('j')); // The current day should be in the appropriate element
    }
}
