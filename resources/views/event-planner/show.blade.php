@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/create.css" />
@endpush

@section('body-content')

@if ( count( $errors ) > 0 )
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@include('event-planner.banner')

@isset ( $success )
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    	<span aria-hidden="true">&times;</span>
	  	</button>
		<strong>Well done!</strong> {{ $success }}
	</div>
@endisset

<div class="container" id="main-content">
<ul class="nav justify-content-center" id="view-nav">
  <li class="nav-item">
    <a class="nav-link" id="view-month" href="{{ route( 'event-planner', [ 'year' => $calendarEvent->start_date->year, 'month' => $calendarEvent->start_date->month, 'submit' => 1 ] ) }}">View month</a>
  </li>
</ul>
<h2 id="create-calendar-event-heading">Calendar Event for {{ $calendarData['calendarHeading'] }}</h2>
	<div id="show-calendar-event-div">
		<div class="row">
			<div class="col mb-2">
				<h3>Name of Event </h3>
				<div id="name">{{ $calendarEvent->name }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>Type of Event </h3>
				<div id="type">{{ $calendarEvent->type }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>Host of the Event </h3>
				<div id="host">{{ $calendarEvent->host }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>Start Date / Time </h3>
				<div id="start_date">{{ $calendarEvent->showStartDate() }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>End Date / Time </h3>
				<div id="end_date">{{ $calendarEvent->showEndDate() }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>Guest List </h3>
				<div id="guest_list">{{ $calendarEvent->guest_list }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>Location</h3>
				<div id="location">{{ $calendarEvent->location }}</div>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col">
				<h3>Message</h3>
				<div id="guest_message">{{ $calendarEvent->guest_message }}</div>
			</div>
		</div>
		<a href="{{ route( 'event-planner.events.edit', $calendarEvent->id ) }}" class="btn btn-primary">Edit</a>		
	</div>
</div>

@endsection