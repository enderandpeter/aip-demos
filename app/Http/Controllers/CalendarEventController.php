<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

class CalendarEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentDate = Carbon::now();
    	
    	$month = $request->input('month') ? $request->input('month') : $currentDate->month;
    	$year = $request->input('year') ? $request->input('year') : $currentDate->year;
    	$date = $month . ' ' . $year;
    	
    	if($request->input('month') && $request->input('year') && !session('errors')){
    		$this->setInputs($request, $date, $month, $year);
    		
    		$this->validate($request, [
    				'month' => 'numeric|min:1|max:12',
    				'year' => 'digits:4',
    				'date' => 'required|date_format:n Y'
    		]);
    	} else if(!session('errors')) {
    		$this->setInputs($request, $date, $month, $year);
    	} else {
    		$month = $currentDate->month;
    		$year = $currentDate->year;
    		$date = $month . ' ' . $year;
    		$this->setInputs($request, $date, $month, $year);
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
     * @param string $date A date of the format 'n Y'
     * @param integer $month The month
     * @param integer $year The year
     */
    private function setInputs(Request $request, $date, $month, $year){
    	$request->replace([
    			'date' => $request->input('date', $date),
    			'month' => $month,
    			'year' => $year
    	]);
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
