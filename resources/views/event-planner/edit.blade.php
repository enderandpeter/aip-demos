@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@include('css.timepicker')
@include('css.jquery-ui-theme-smoothness')

@if ( !empty( $validationMessages ) )
	@push ('scripts')
		<script src="/js/event-planner/edit.js"></script>
		<script>
			window.sessionStorage.setItem('validationMessages', '<?php echo $validationMessages ?>'); 
		</script>
	@endpush
@endif

@include('scripts.timepicker')
@include('scripts.jquery-ui')

@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/create.css" />
@endpush

@section('body-content')

@include('event-planner.banner')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container" id="main-content">
<h2 id="create-calendar-event-heading">Edit Calendar Event for <span id="calendar-heading">{{ $calendarData['calendarHeading'] }}</span></h2>
	<form id="create-calendar-event-form" method="post" action="{{ route( 'event-planner.events.update', $id ) }}">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="PUT">
		<div class="form-group mb-2">
			<label for="title">Name of Event </label>
			<input type="text" autofocus name="name" id="name" required maxlength="191" class="form-control" value="{{ $name ?? old('name') }}" />
		</div>
		<div class="form-group">
			<label for="type">Type of Event </label>
			<input type="text" name="type" id="type" required maxlength="191" class="form-control" value="{{ $type ?? old('type') }}" />
			<small id="event-type-help" class="form-text">E.g., Birthday Party, Conference Talk, Wedding, etc.</small>
		</div>
		<div class="form-group">
			<label for="host">Host of the Event </label>
			<input type="text" name="host" required maxlength="191" class="form-control" value="{{ $host ?? old('host') }}" />
			<small id="event-type-help" class="form-text">E.g., An individual’s name or an organization, etc.</small>
		</div>
		<div class="form-group">
			<label for="start_date">Start Date / Time </label>
			<input type="text" name="start_date" required id="start_date" value="{{ $start_date ?? old('start_date') }}" class="form-control date" />
		</div>
		<div class="form-group">
			<label for="end_date">End Date / Time </label>
			<input type="text" name="end_date" required id="end_date" class="form-control date" value="{{ $end_date ?? old('end_date') }}" />
		</div>
		<div class="form-group">
			<label for="guest_list">Guest List </label>
			<textarea id="guest_list" name="guest_list" required maxlength="1000" class="form-control">{{ $guest_list ?? old('guest_list') }}</textarea>
		</div>
		<div class="form-group">
			<label for="location">Location</label>
			<input type="text" id="location" name="location" maxlength="191" required class="form-control" value="{{ $location ?? old('location') }}" />
		</div>
		<div class="form-group">
			<label for="guest_message">Message</label>
			<textarea id="guest_message" name="guest_message" class="form-control" maxlength="5000">{{ $guest_message ?? old('guest_message') }}</textarea>
			<small id="message-help" class="form-text">An optional message to the guests with additional information about the event</small>
		</div>
		<button type="submit" class="btn btn-primary float-left mb-5">Update</button>				
	</form>
	<form id="delete-form" class="float-right" method="post" action="{{ route( 'event-planner.events.destroy', $id ) }}">
		{{ csrf_field() }}
		<input type="hidden" name="_method" value="DELETE">
		<button type="submit" id="delete-button" class="btn btn-danger">Delete</button>
	</form>	
</div>

<div id="delete-confirm" title="Delete this event?" hidden>
  <p>
  	<span class="ui-icon ui-icon-alert float-left mt-12 mr-12 mb-20"></span>
  	<span>This calendar event will be permanently deleted and cannot be recovered. Are you sure?</span>
  </p>
</div>

@endsection