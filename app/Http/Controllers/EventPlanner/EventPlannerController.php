<?php
namespace App\Http\Controllers\EventPlanner;

use App\Http\Controllers\Controller as Controller;

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
