@extends('layouts.event-planner')

@section('title')
Event Planner
@endsection

@include('css.datetimepicker')

@push ('scripts')
	<script src="/js/event-planner/create.js"></script>
@endpush

@include('scripts.datetimepicker')

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

<h2 id="create-calendar-event-heading">Create a Calendar Event for {{ $calendarData['calendarHeading'] }}</h2>
<div class="container">
	<form id="create-calendar-event-form">
		<div class="form-group">
			<label>Name of Event </label>
			<input type="text" autofocus name="title" required maxlength="191" class="form-control" />
		</div>
		<div class="form-group">
			<label>Type of Event </label>
			<input type="text" autofocus name="type" required maxlength="191" class="form-control" />
			<small id="event-type-help" class="form-text text-muted">E.g., Birthday Party, Conference Talk, Wedding, etc.</small>
		</div>
		<div class="form-group">
			<label>Host of the Event </label>
			<input type="text" autofocus name="host" maxlength="191" class="form-control" />
			<small id="event-type-help" class="form-text text-muted">E.g., An individual’s name or an organization, etc.</small>
		</div>
		<div class="form-group">
			<label>Start Date / Time </label>
			<input type="text" autofocus name="start_date" id="start_date" data-startdate="{{ $startDate }}" class="form-control" />
		</div>
	</form>
</div>

@endsection