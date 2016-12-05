<?php

namespace App\Http\Controllers\EventPlanner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller; 

use App\EventPlanner\CalendarRequest;

use Illuminate\Support\Facades\Auth;

class CalendarEventController extends Controller
{
	/**
	 * Get the guard that this controller uses for user authentication. 
	 * 
	 * @return string The name of guard for this controller
	 */
	protected function getGuard(){
		return 'eventplanner';
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$calendarData = CalendarRequest::getCalendarData($request, $this);
    	$logged_in = Auth::guard($this->getGuard())->check();
    	$viewdata = array_merge($request->input(), $calendarData, [
    			'logged_in' => $logged_in,
    			'user' => Auth::guard($this->getGuard())->user()
    	]);
    	
    	return view('event-planner')->with($viewdata);
    }    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
