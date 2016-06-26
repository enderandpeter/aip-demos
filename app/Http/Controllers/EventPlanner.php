<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

use Input;

class EventPlanner extends Controller
{		
	/**
	 * Get the requested calendar by month and year
	 * 
	 * @param Request $request
	 */
    public function getCalendar(Request $request){
    	$currentDate = Carbon::now();
    	
    	$month = $request->input('month') ? $request->input('month') : $currentDate->month;
    	$year = $request->input('year') ? $request->input('year') : $currentDate->year;
    	$date = $month . ' ' . $year;
    	
    	if($request->input('month') && $request->input('year') && !session('errors')){
    		$this->setInputs($request, $date, $month, $year);
    		
    		$this->validate($request, [
    				'date' => 'required|date_format:n Y'
    		]);
    	} else if(!session('errors')) {
    		$this->setInputs($request, $date);
    	} else {
    		/*
    		 * If the requested calendar date could not be determined, set the default values
    		 * for the current month and year.
    		 */
    		$month = $currentDate->month;
    		$year = $currentDate->year;
    		$date = $month . ' ' . $year;
    		$this->setInputs($request, $date);
    	}
    	
    	$calendarDate = Carbon::createFromFormat('n Y', $date);
    	
    	$calendarData = [
    		'calendarHeading' => $calendarDate->format('F') . ' ' . $calendarDate->format('Y') 	
    	];
    	
    	$viewdata = array_merge($request->input(), $calendarData);
    	
    	return view('event-planner')->with($viewdata);
	}
	
	/**
	 * Set the expected request inputs
	 * 
	 * @param Request $request The request for which the inputs are being set
	 * @param string $date The date in the format 'n Y'
	 */
	private function setInputs(Request $request, $date = ''){
		if(empty($date)){
			return;
		}
		
		$request->replace([
				'date' => $request->input('date', $date),
		]);
	}
}
