<?php

namespace App\EventPlanner;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

/**
 * Handle managing data for Event Planner requests
 * 
 * @author Spencer
 *
 */
class CalendarRequest{
	/**
	 * Get the calendar data for the event planner request
	 * 
	 * @param Request $request The request object for this request
	 * @param Controller $controller The controller object handling the calendar request
	 * @return string[]
	 */
	public static function getCalendarData(Request $request, Controller $controller){
		$currentDate = Carbon::now();
		 
		$month = $request->input('month') ? $request->input('month') : $currentDate->month;
		$year = $request->input('year') ? $request->input('year') : $currentDate->year;
		$date = $month . ' ' . $year;
		 
		if($request->input('month') && $request->input('year') && !session('errors')){
			self::setInputs($request, $date, $month, $year);
		
			$controller->validate($request, [
					'month' => 'numeric|min:1|max:12',
					'year' => 'digits:4',
					'date' => 'required|date_format:n Y'
			]);
		} else if(!session('errors')) {
			self::setInputs($request, $date, $month, $year);
		} else {
			$month = $currentDate->month;
			$year = $currentDate->year;
			$date = $month . ' ' . $year;
			self::setInputs($request, $date, $month, $year);
		}
		 
		$calendarDate = Carbon::createFromFormat('n Y', $date);
		 
		$calendarData = [
				'calendarHeading' => $calendarDate->format('F') . ' ' . $calendarDate->format('Y')
		];
		
		return $calendarData;
	}
	
	/**
	 * Set the expected request inputs
	 *
	 * @param Request $request The request for which the inputs are being set
	 * @param string $date A date of the format 'n Y'
	 * @param integer $month The month
	 * @param integer $year The year
	 */
	private static function setInputs(Request $request, $date, $month, $year){
		$request->replace([
				'date' => $request->input('date', $date),
				'month' => $month,
				'year' => $year
		]);
	}
}