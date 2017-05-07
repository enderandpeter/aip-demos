@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@include('css.datetimepicker')

@include('scripts.datetimepicker')

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

<div id="banner">
	<div id="user_controls">
		<div id="user_info">
			<span id="user_greeting">Hello, {{ $user->name }}!</span>
			<span id="auth_controls">
				<form id="logout-form" action="{{ route( 'event-planner.logout' ) }}" method="POST">
					<button id="logout-button">Logout</button>
				    {{ csrf_field() }}
                   </form>
			</span>
		</div>
	</div>
</div>

@isset ( $success )
	<div class="alert alert-success" role="alert">
	  <strong>Well done!</strong> {{ $success }}
	</div>
@endisset

<div class="container" id="main-content">
<h2 id="create-calendar-event-heading">Create a Calendar Event for {{ $calendarData['calendarHeading'] }}</h2>
	<div id="show-calendar-event-div">
		<div class="row">
			<div class="col">
				<h2>Name of Event </h2>
				<div id="name">{{ $calendarEvent->name }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2>Type of Event </h2>
				<div id="type">{{ $calendarEvent->type }}</div>
				<small id="event-type-help" class="form-text">E.g., Birthday Party, Conference Talk, Wedding, etc.</small>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2>Host of the Event </h2>
				<div id="host">{{ $calendarEvent->host }}</div>
				<small id="event-type-help" class="form-text">E.g., An individual’s name or an organization, etc.</small>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2>Start Date / Time </h2>
				<div id="start_date">{{ $calendarEvent->start_date }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2>End Date / Time </h2>
				<div id="end_date">{{ $calendarEvent->end_date }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2 >Guest List </h2>
				<div id="guest_list">{{ $calendarEvent->guest_list }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2>Location</h2>
				<div id="location">{{ $calendarEvent->location }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<h2>Message</h2>
				<div id="guest_message">{{ $calendarEvent->guest_message }}</div>
				<small id="message-help" class="form-text">An optional message to the guests with additional information about the event</small>
			</div>
		</div>
		<a href="{{ route( 'event-planner.events.edit', $calendarEvent->id ) }}" type="submit" class="btn btn-primary">Edit</a>		
	</div>
</div>

@endsection