<?php

namespace App\Http\Controllers\EventPlanner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller; 

use App\EventPlanner\CalendarRequest;
use App\EventPlanner\CalendarEvent;
use App\EventPlanner\ValidationData;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;

class CalendarEventController extends EventPlannerController
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
    	$calendarData = (new CalendarRequest)->getCalendarData($request);
    	$logged_in = Auth::guard($this->getGuard())->check();
    	
    	$currentDate = $calendarData['currentDate'];
    	$currentDay = $currentDate->day;
    	
    	$viewdate = $calendarData['calendarDate'];
    	
    	$month = $viewdate->month;
    	$year = $viewdate->year;
    	
    	/* Initial days and weeks vars ... */
    	$running_day = date('w',mktime(0, 0, 0, $month, 1, $year));
    	$days_in_month = date('t',mktime(0,0,0, $month, 1, $year));
    	$days_in_this_week = 1;
    	$day_counter = 0;
    	$dates_array = [];
    	
    	$headings = [ 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday' ];
    	
    	$begin = new DateTime( $viewdate->copy()->startofYear() );
    	$end = new DateTime( $viewdate->copy()->endofYear() );
    	 
    	$interval = DateInterval::createFromDateString('1 month');
    	$period = new DatePeriod($begin, $interval, $end);
    	
    	$calendarSelectionOptions = [];
    	
    	foreach($period as $datetime){
    		$selected = '';
    		$carbonDate = Carbon::instance($datetime);
    		$monthName = $carbonDate->format('F');
    		$monthNumber = $carbonDate->format('n');
    		 
    		if($viewdate->month === (int) $monthNumber){
    			$selected = ' selected="selected"';
    		}
    		
    		$calendarSelectionOptions[] = [
    			'selected' => $selected,
    			'monthNumber' => $monthNumber,
    			'monthName' => $monthName
    		];
    	}
    	
    	$viewdata = array_merge($request->input(), $calendarData, [
    			'logged_in' => $logged_in,
    			'user' => Auth::guard($this->getGuard())->user(),
    			'viewdate' => $viewdate,
    			'month' => $month,
    			'year' => $year,
    			'currentDate' => $currentDate,
    			'currentDay' => $currentDay,
    			'running_day' => $running_day,
    			'days_in_month' => $days_in_month,
    			'days_in_this_week' => $days_in_this_week,
    			'day_counter' => $day_counter,
    			'dates_array' => $dates_array,
    			'headings' => $headings,
    			'calendarSelectionOptions' => $calendarSelectionOptions
    	]);
    	
    	return view('event-planner.index')->with($viewdata);
    }    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
    	$calendarData = ( new CalendarRequest )->getCalendarData( $request );
    	
    	$calendarDate = $calendarData[ 'calendarDate' ];
    	$calendarData[ 'calendarHeading' ] = $calendarDate->toFormattedDateString();  
    	
    	if( !Auth::guard( $this->getGuard() )->user() ){
        	return redirect()->route( 'event-planner' );
        } 
        
        $validationMessages = json_encode( $this->getValidationMessagesArray( 'create-event' ) );
        
        $viewdata = [
        	'user' => 	Auth::guard( $this->getGuard() )->user(),
        	'calendarData' => $calendarData,
        	'startDate' => $calendarDate->format( 'Y/m/d' ),
        	'validationMessages' => $validationMessages
        ];
        
        return view( 'event-planner.create' )->with( $viewdata );
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
    	$validationData = new ValidationData();
    	$validationRules = $validationData->getData( 'create-event' );
    	
    	$start_date = new Carbon( $request->start_date );
    	$end_date = new Carbon( $request->end_date ) ;
    	
    	$request->replace( array_merge( $request->all(), [ 
    			'user_id' => Auth::user()->id, 
    			'start_date' => $start_date->format( 'm/d/Y H:i' ),
    			'end_date' => $end_date->format( 'm/d/Y H:i' )
    	] ) );
    	$this->validate( $request, $validationRules );
    	
    	$request->replace( array_merge( $request->all(), [
    			'start_date' => $start_date,
    			'end_date' => $end_date
    	] ) );
    	
    	$calendarEvent = CalendarEvent::create( $request->all() );
    	
    	$data = [
    		'success' => 'The event was created successfully.'
    	];
    	
    	return redirect( route( 'event-planner.events.show', $calendarEvent->id ) )->with( $data );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
    	$calendarEvent = CalendarEvent::findOrFail( $id );
    	
    	$calendarData = [
    			'calendarDate' => $calendarEvent->start_date,
    			'calendarHeading' => $calendarEvent->start_date->toFormattedDateString()
    	];
    	
        $data = [
        	'user' => 	Auth::guard( $this->getGuard() )->user(),
        	'calendarEvent' => $calendarEvent,
        	'calendarData' => $calendarData,
        ];
        
        if( session('success') ){
        	$data['success'] = session('success');
        }
        
        return view( 'event-planner.show' )->with( $data );
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
