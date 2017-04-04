@extends('layouts.master')

@push('nav-list-items')	
	@if ( Route::current()->uri() === 'frogger' )
		<li class="active">Frogger</li>
	@else
		<li><a href="/frogger">Frogger</a></li>
	@endif
@endpush

@include('scripts.jquery')
@include('scripts.ko')
@include('scripts.bootstrap')
@push ('scripts')	
	<script src="js/frogger/main.js"></script>
    <script src="js/frogger/resources.js"></script>
    <script src="js/frogger/app.js"></script>
    <script src="js/frogger/engine.js"></script>
@endpush

@section('body')
<body id="frogger-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection