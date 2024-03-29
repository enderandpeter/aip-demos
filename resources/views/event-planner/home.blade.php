@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@section('body-content')

@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/home.css" />
@endpush

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
	@include('event-planner.banner')
	
	@isset ( $deleted )
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    	<span aria-hidden="true">&times;</span>
	  	</button>
		<strong>Done!</strong> {{ $deleted }}
	</div>
	@endisset
	
	<ul class="nav justify-content-center" id="view-nav">
	  <li class="nav-item">
	    <a class="nav-link" id="view-calendarevents" href="{{ route( 'event-planner.events.index' ) }}">View all events</a>
	  </li>
	</ul>
	
	{{--
	// Courtesy of David Walsh; https://davidwalsh.name/php-calendar
	  Only show a calendar if a $date variable was sent to this view 
	 --}}
	@if(!empty($date))
	  <div class="container" id="calendar">
		<h2 id="calendarHeading">{{ $calendarHeading }}</h2>
		<form id="setDate" method="get" class="form-inline mb-2">
		  <div class="form-group">
		  	<label for="month" class="mb-2 mr-sm-2 mb-sm-0">Enter Month: </label>
			<select id="month" name="month" class="form-control mb-2 mr-sm-2 mb-sm-0">		    
				    @foreach($calendarSelectionOptions as $calendarSelectionOption)			    	
				    	<option value="{{ $calendarSelectionOption['monthNumber'] }}"{{ $calendarSelectionOption['selected'] }}>{{ $calendarSelectionOption['monthName'] }}</option>
				    @endforeach
			</select>
		  	<label for="year" class="mb-2 mr-sm-2 mb-sm-0">Enter Year: </label>
		  	<input type="number" name="year" id="year" class="form-control mb-2 mr-sm-2 mb-sm-0" value="{{ $viewdate->year }}" />
		  
		  	<input type="hidden" name="day" value="1" />
		  	
		  	<button class="btn btn-primary mb-2 mr-sm-2 mb-sm-0" type="submit" name="submit" value="go">Go</button>
		  	<button class="btn btn-primary mb-2 mr-sm-2 mb-sm-0" type="submit" name="submit" value="today">Today</button>
		  </div>
		</form>
		
		{{-- draws a calendar --}}
		
		{{-- draw table --}}
		<table cellpadding="0" cellspacing="0" class="calendar table table-responsive">
			<tr class="calendar-row">
				<td class="calendar-day-head">
					<?php 
					 echo implode('</td><td class="calendar-day-head">',$headings);
					?>
				</td>
			</tr>
		{{-- row for week one --}}
			<tr class="calendar-row">	
				{{-- print "blank" days until the first of the current week --}}
				@for($x = 0; $x < $running_day; $x++)
					<td class="calendar-day-np"> </td>
					<?php $days_in_this_week++; ?>
				@endfor
				
		{{-- keep going with days.... --}}
		
		@for($list_day = 1; $list_day <= $days_in_month; $list_day++)
			<?php 
			$calendar_day_class = 'calendar-day calendar-day-' . $list_day;
			$calendar_day_id_attr = '';
			?>
			@if($list_day === $currentDay && $viewdate->month === $currentDate->month && $viewdate->year === $currentDate->year)
				<?php $calendar_day_id_attr .= ' id=today '; ?>
			@endif
			<td{{ $calendar_day_id_attr }} class="{{ $calendar_day_class }}">
				{{-- add in the day number --}}
				<div class="day-number">{{ $list_day }}</div>
				<div class="add-link float-right">
					<a href="{{ route( 'event-planner.events.create', [ 'month' => $month, 'year' => $year, 'day' => $list_day, 'submit' => 1 ] ) }}">Add</a>
				</div>
		
				{{-- QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! --}}
				@if( @isset( $calendarEvents[ $list_day ] ) )					
					@foreach( $calendarEvents[ $list_day ] as $calendarEvent )
						<div class="calendarevent-entry">
							<a href="{{ route( 'event-planner.events.show', $calendarEvent->id ) }}" class="calendarevent-link">
							{{ $calendarEvent->name }} {{ $calendarEvent->getStartTime() }}
						</a>
						</div>					
					@endforeach			
				@endif
			</td>
			@if($running_day == 6)
				</tr>
				@if(($day_counter+1) != $days_in_month)
					<tr class="calendar-row">
				@endif
				<?php 
				$running_day = -1;
				$days_in_this_week = 0;
				?>
			@endif
			<?php $days_in_this_week++; $running_day++; $day_counter++; ?>
		@endfor
		
		
		{{-- finish the rest of the days in the week --}}
		@if($days_in_this_week < 8)
			@for($x = 1; $x <= (8 - $days_in_this_week); $x++)
				<td class="calendar-day-np"> </td>
			@endfor
		@endif
		
		{{-- final row --}}
		</tr>	
		{{-- end the table --}}
	</table>
	</div>	
	@endif {{-- endif $date --}}
@else
	<div id="guest_welcome" class="container mb-4">
		<h1 id="main-heading">
			<div>Welcome to Event Planner!</div> 
			<small class="new-user-intro">Please <a href="{{ route('event-planner.login.show') }}" id="banner-sign-in-link">sign in</a> or <a href="{{ route('event-planner.register.show') }}" id="banner-register-link">create an account</a> to get started.</small>
		</h1>
	</div>
@endif


@endsection