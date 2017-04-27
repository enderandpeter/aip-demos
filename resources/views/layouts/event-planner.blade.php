@extends('layouts.master')

@include('nav.demos')
@push('nav-list-items')	
	@if ( Route::currentRouteName() === 'event-planner' )
		<li class="breadcrumb-item active">Event Planner</li>
	@else
		<li class="breadcrumb-item"><a href="/event-planner">Event Planner</a></li>
	@endif
@endpush


@include('css.main')
@include('css.bootstrap4')

@include('scripts.bootstrap4')
@include('scripts.ko')
@include('scripts.jquery')


@section('body')
<body id="event-planner-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection