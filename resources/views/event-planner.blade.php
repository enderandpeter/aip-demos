@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/event-planner/main.css" />
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

<?php
// Courtesy of David Walsh; https://davidwalsh.name/php-calendar

if(!empty($month) && !empty($year)){
/* draws a calendar */

/* draw table */
$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

/* table headings */
$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

/* days and weeks vars now ... */
$running_day = date('w',mktime(0,0,0,$month,1,$year));
$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
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
	$calendar.= '<td class="calendar-day">';
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
	<h2>{{ $calendarHeading }}</h2>
	{!! $calendar !!}
<?php 
} // if $month and $year
?>

@endsection