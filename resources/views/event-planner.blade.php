<?php 
	use Carbon\Carbon;
?>
@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/main.css" />
@endpush

@include('scripts.jquery')
@include('scripts.ko')
@include('scripts.bootstrap')

@push('nav-list-items')	
	@if ( Route::current()->getPath() === 'event-planner' )
		<li class="active">Event Planner</li>
	@else
		<li><a href="/event-planner">Event Planner</a></li>
	@endif
@endpush

@section('title')
Event Planner
@endsection

@section('body-content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($logged_in)	
	<div id="banner">
		<div id="user_controls">
			<div id="user_info">
				<span id="user_greeting">Hello, {{ $user->name }}!</span>
				<span id="auth_controls">
					<a href="{{ route('event-planner.logout') }}">Logout</a>
				</span>
			</div>
		</div>
	</div>
@else
	<div id="guest_welcome" class="container">
		<div class="well">
			Welcome to Event Planner! Please <a href="{{ route('event-planner.login.show') }}" id="banner-sign-in-link">sign in</a> or <a href="{{ route('event-planner.register.show') }}" id="banner-register-link">create an account</a> to get started.
		</div>
	</div>
@endif

<?php
// Courtesy of David Walsh; https://davidwalsh.name/php-calendar
/*
 * Only show a calendar if a $date variable was sent to this view
 */
if(!empty($date)){	
	// Create date
	$viewdate = Carbon::createFromFormat('n Y', $date);
	$month = $viewdate->month;
	$year = $viewdate->year;
	
	$currentDate = Carbon::now();
	$currentDay = $currentDate->day; 
	
	/* draws a calendar */
	
	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';
	
	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';
	
	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0, 0, 0, $month, 1, $year));
	$days_in_month = date('t',mktime(0,0,0, $month, 1, $year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();
	
	/* row for week one */
	$calendar.= '<tr class="calendar-row">';
	
	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;
	
	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar_day_class = 'calendar-day';
		$calendar_day_id_attr = '';
		if($list_day === $currentDay && $viewdate->month === $currentDate->month && $viewdate->year === $currentDate->year){
			$calendar_day_id_attr .= ' id="today" ';
		}
		$calendar.= '<td ' . $calendar_day_id_attr . 'class="' . $calendar_day_class .'">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';
	
			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			$calendar.= str_repeat('<p> An entry</p>',2);
			
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;
	
	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;
	
	/* final row */
	$calendar.= '</tr>';
	
	/* end the table */
	$calendar.= '</table>';
?>	
	<h2 id="calendarHeading">{{ $calendarHeading }}</h2>
	<form id="setDate" method="get" class="form-inline">
	  <div class="form-group">
	  	<label for="month">Enter Month: </label>
		<select id="month" name="month" class="form-control">
		    <?php	    			    
			    $begin = new DateTime( $viewdate->copy()->startofYear() );
			    $end = new DateTime( $viewdate->copy()->endofYear() );
			    
			    $interval = DateInterval::createFromDateString('1 month');
			    $period = new DatePeriod($begin, $interval, $end);
			?>    
			    @foreach($period as $datetime)
			    	<?php 
			    		$selected = '';
				    	$carbonDate = Carbon::instance($datetime);
				    	$monthName = $carbonDate->format('F');
				    	$monthNumber = $carbonDate->format('n');
				    	
				    	if($viewdate->month === (int) $monthNumber){
				    		$selected = ' selected="selected"';
				    	}
			    	?>
			    	<option value="{{ $monthNumber }}"{{ $selected }}>{{ $monthName }}</option>
			    @endforeach
		    
		</select>
	  </div>
	  <div class="form-group">
	  	<label for="year">Enter Year: </label>
	  	<input type="number" name="year" id="year" class="form-control" value="{{ $viewdate->year }}" />
	  </div>
	  <button class="btn btn-default" type="submit">Go</button>
	</form>
	<form id="goToToday" class="form-inline" method="get">
		<input type="hidden" name="month" id="month" value="{{ $currentDate->month }}"/>
		<input type="hidden" name="year" id="year" value="{{ $currentDate->year }}" />
		<button class="btn btn-default" type="submit">Today</button>
	</form>	
	{!! $calendar !!}
<?php 
} // if $month and $year
?>

@endsection