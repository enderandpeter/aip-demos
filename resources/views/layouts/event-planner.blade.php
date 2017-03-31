@extends('layouts.master')

@push('nav-list-items')	
	@if ( Route::currentRouteName() === 'event-planner' )
		<li class="active">Event Planner</li>
	@else
		<li><a href="/event-planner">Event Planner</a></li>
	@endif
@endpush

@include('scripts.jquery')
@include('scripts.ko')
@include('scripts.bootstrap')

@section('body')
<body id="event-planner-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection