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

	public function home( Request $request ){
		$calendarData = ( new CalendarRequest)->getCalendarData( $request );
		$logged_in = Auth::guard( $this->getGuard() )->check();

		/** @var  $currentDate Carbon */
		$currentDate = $calendarData[ 'currentDate' ];
		$currentDay = $currentDate->day;

        /** @var  $currentDate Carbon */
		$viewdate = $calendarData[ 'calendarDate' ];

		$month = $viewdate->month;
		$year = $viewdate->year;

		/* Initial days and weeks vars ... */
		$running_day = date( 'w', mktime( 0, 0, 0, $month, 1, $year ) );
		$days_in_month = date( 't', mktime( 0,0,0, $month, 1, $year ) );
		$days_in_this_week = 1;
		$day_counter = 0;
		$dates_array = [];

		$headings = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];

		$begin = new DateTime( $viewdate->copy()->startofYear() );
		$end = new DateTime( $viewdate->copy()->endofYear() );

		$interval = DateInterval::createFromDateString( '1 month' );
		$period = new DatePeriod( $begin, $interval, $end );

		$calendarSelectionOptions = [];

		foreach($period as $datetime){
			$selected = '';
			$carbonDate = Carbon::instance( $datetime );
			$monthName = $carbonDate->format( 'F' );
			$monthNumber = $carbonDate->format( 'n' );

			if($viewdate->month === (int) $monthNumber){
				$selected = ' selected="selected"';
			}

			$calendarSelectionOptions[] = [
					'selected' => $selected,
					'monthNumber' => $monthNumber,
					'monthName' => $monthName
			];
		}

		$viewdata = array_merge( $request->input(), $calendarData, [
				'logged_in' => $logged_in,
				'user' => Auth::guard( $this->getGuard() )->user(),
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
				'calendarSelectionOptions' => $calendarSelectionOptions,
				'calendarEvents' => CalendarEvent::getEventsByYearAndMonth($year, $month)
		]);

		if( session( 'deleted' ) ){
			$viewdata[ 'deleted' ] = 'The event was deleted successfully.';
		}

		return view( 'event-planner.home' )->with( $viewdata );
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
    	if( !Auth::guard( $this->getGuard() )->user() ){
    		return redirect()->route( 'event-planner' );
    	}

    	$viewdata = [
    			'user' => 	Auth::guard( $this->getGuard() )->user(),
    			'calendarevents' => CalendarEvent::all()
    	];

    	return view( 'event-planner.index' )->with( $viewdata );
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

        $datepicker_start = clone $calendarData['calendarDate'];

        $viewdata = [
        	'user' => 	Auth::guard( $this->getGuard() )->user(),
        	'calendarData' => $calendarData,
        	'startDate' => $calendarDate->format( 'Y/m/d' ),
        	'validationMessages' => $validationMessages,
        	'datepicker_start' => $datepicker_start->format(CalendarEvent::$datepicker_format)
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
    	$date_format = CalendarEvent::$date_format;

    	if( !empty( $request->start_date ) ){
    		try{
    			$start_date = new Carbon( $request->start_date );
    			$start_date_input = $start_date->format( $date_format );
    		}
    		catch ( Exception $e ){
    			$start_date = '';
    		}
    	} else {
    		$start_date_input = $request->start_date;
    	}

    	if( !empty( $request->end_date ) ){
    		try{
    			$end_date = new Carbon( $request->end_date ) ;
    			$end_date_input = $end_date->format( $date_format );
    		}
    		catch ( Exception $e ){
    			$end_date = '';
    		}
    	} else {
    		$end_date_input = $request->end_date;
    	}

    	$request->replace( array_merge( $request->all(), [
    			'user_id' => Auth::user()->id,
    			'start_date' => $start_date_input,
    			'end_date' => $end_date_input
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

        if( session( 'success' ) ){
        	$data[ 'success' ] = session( 'success' );
        }

        return view( 'event-planner.show' )->with( $data );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
    	$calendarEvent = CalendarEvent::findOrFail( $id );

    	$calendarData = [
    			'calendarDate' => $calendarEvent->start_date,
    			'calendarHeading' => $calendarEvent->start_date->toFormattedDateString()
    	];

    	$validationMessages = json_encode( $this->getValidationMessagesArray( 'create-event' ) );

    	$data = [
    			'user' => 	Auth::guard( $this->getGuard() )->user(),
    			'calendarEvent' => $calendarEvent,
    			'calendarData' => $calendarData,
    			'validationMessages' => $validationMessages,
    			'date_format' => CalendarEvent::$date_format
    	];

    	$calendarEventArray = $calendarEvent->toArray();

    	$calendarEventArray[ 'start_date' ] = $calendarEvent->showStartDate();
    	$calendarEventArray[ 'end_date' ] = $calendarEvent->showEndDate();

    	$data = array_merge( $data, $calendarEventArray );

    	return view( 'event-planner.edit' )->with( $data );
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
    	$calendarEvent = CalendarEvent::findOrFail( $id );

    	$validationData = new ValidationData();
    	$validationRules = $validationData->getData( 'create-event' );
    	$date_format = CalendarEvent::$date_format;

    	if( !empty( $request->start_date ) ){
    		try{
    			$start_date = new Carbon( $request->start_date );
    			$start_date_input = $start_date->format( $date_format );
    		}
    		catch ( Exception $e ){
    			$start_date = '';
    		}
    	} else {
    		$start_date_input = $request->start_date;
    	}

    	if( !empty( $request->end_date ) ){
    		try{
    			$end_date = new Carbon( $request->end_date ) ;
    			$end_date_input = $end_date->format( $date_format );
    		}
    		catch ( Exception $e ){
    			$end_date = '';
    		}
    	} else {
    		$end_date_input = $request->end_date;
    	}

    	$request->replace( array_merge( $request->all(), [
    			'user_id' => Auth::user()->id,
    			'start_date' => $start_date_input,
    			'end_date' => $end_date_input
    	] ) );
    	$this->validate( $request, $validationRules );

    	$request->replace( array_merge( $request->all(), [
    			'start_date' => $start_date,
    			'end_date' => $end_date
    	] ) );

    	$calendarEvent->fill( $request->all() );
    	$calendarEvent->save();

    	$data = [
    			'success' => 'The event was edited successfully.'
    	];

    	return redirect( route( 'event-planner.events.show', $calendarEvent->id ) )->with( $data );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	$calendarEvent = CalendarEvent::findOrFail( $id );

    	$calendarEvent->delete();

    	$data = [
    			'deleted' => 'The event was deleted successfully.'
    	];

    	return redirect( route( 'event-planner' ) )->with( $data );
    }
}
