@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@section('body-content')

@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/main.css" />
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
	<div id="banner">
		<div id="user_controls">
			<div id="user_info">
				<span id="user_greeting">Hello, {{ $user->name }}!</span>
				<span id="auth_controls">
					<form id="logout-form" action="{{ route('event-planner.logout') }}" method="POST">
						<button id="logout-button">Logout</button>
					    {{ csrf_field() }}
                    </form>
				</span>
			</div>
		</div>
	</div>
	
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
			$calendar_day_class = 'calendar-day';
			$calendar_day_id_attr = '';
			?>
			@if($list_day === $currentDay && $viewdate->month === $currentDate->month && $viewdate->year === $currentDate->year)
				<?php $calendar_day_id_attr .= ' id=today '; ?>
			@endif
			<td{{ $calendar_day_id_attr }} class="{{ $calendar_day_class }}">
				{{-- add in the day number --}}
				<div class="day-number">{{ $list_day }}</div>
		
				{{-- QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! --}}
				<p> An entry</p> <p> An entry</p>			
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