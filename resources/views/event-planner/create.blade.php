@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@include('css.datetimepicker')

@push ('scripts')
	<script src="/js/event-planner/create.js"></script>
	<script>
		window.sessionStorage.setItem('validationMessages', '<?php echo $validationMessages ?>'); 
	</script>
@endpush

@include('scripts.datetimepicker')

@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/create.css" />
@endpush

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

<div class="container" id="main-content">
<h2 id="create-calendar-event-heading">Create a Calendar Event for {{ $calendarData['calendarHeading'] }}</h2>
	<form id="create-calendar-event-form">
		<div class="form-group">
			<label for="title">Name of Event </label>
			<input type="text" autofocus name="name" id="name" required maxlength="191" class="form-control" />
		</div>
		<div class="form-group">
			<label>Type of Event </label>
			<input type="text" name="type" id="type" required maxlength="191" class="form-control" />
			<small id="event-type-help" class="form-text">E.g., Birthday Party, Conference Talk, Wedding, etc.</small>
		</div>
		<div class="form-group">
			<label>Host of the Event </label>
			<input type="text" name="host" required maxlength="191" class="form-control" />
			<small id="event-type-help" class="form-text">E.g., An individual’s name or an organization, etc.</small>
		</div>
		<div class="form-group">
			<label>Start Date / Time </label>
			<input type="text" name="start_date" required id="start_date" data-startdate="{{ $startDate }}" class="form-control" />
		</div>
		<div class="form-group">
			<label>End Date / Time </label>
			<input type="text" name="end_date" required id="end_date" class="form-control" />
		</div>
		<div class="form-group">
			<label for="guest_list">Guest List </label>
			<textarea id="guest_list" name="guest_list" required maxlength="1000" class="form-control">
			</textarea>
		</div>
		<div class="form-group">
			<label for="location">Location</label>
			<input type="text" id="location" name="location" maxlength="191" required class="form-control" />
		</div>
		<div class="form-group">
			<label for="guest_message">Message</label>
			<textarea id="guest_message" class="form-control" maxlength="5000">
			</textarea>
			<small id="message-help" class="form-text">An optional message to the guests with additional information about the event</small>
		</div>
		<button type="submit" class="btn btn-primary">Create</button>		
	</form>
</div>

@endsection