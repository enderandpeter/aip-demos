<?php
namespace App\Http\Controllers\EventPlanner;

use App\Http\Controllers\Controller as Controller;

use App\EventPlanner\CalendarRequest;
use App\EventPlanner\CalendarEvent;
use App\EventPlanner\ValidationData;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;

class EventPlannerController extends Controller{
	use ValidatesEventPlannerRequests;
	
	/**
	 * Get the guard that this controller uses for user authentication.
	 *
	 * @return string The name of guard for this controller
	 */
	protected function getGuard(){
		return 'eventplanner';
	}
}