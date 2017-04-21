@extends('layouts.master')

@push('nav-list-items')	
	@if ( Route::current()->uri() === 'frogger' )
		<li class="breadcrumb-item active">Frogger</li>
	@else
		<li class="breadcrumb-item"><a href="/frogger">Frogger</a></li>
	@endif
@endpush

@include('css.main')
@include('css.bootstrap')

@include('scripts.bootstrap')
@include('scripts.ko')
@include('scripts.jquery')
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