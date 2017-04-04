@extends('layouts.master')

@include('nav.demos')
@push('nav-list-items')	
	@if ( Route::currentRouteName() === 'event-planner' )
		<li class="active">Event Planner</li>
	@else
		<li><a href="/event-planner">Event Planner</a></li>
	@endif
@endpush

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="/css/event-planner/main.css" />
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