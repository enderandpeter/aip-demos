<?php
namespace App\Http\Controllers\EventPlanner;

use App\Http\Controllers\Controller as Controller;

use App\Http\Controllers\EventPlanner\ValidatesEventPlannerRequests;

class EventPlannerController extends Controller{
	use ValidatesEventPlannerRequests;
}