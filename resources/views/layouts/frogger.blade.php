@extends('layouts.master')

@push('nav-list-items')	
	@if ( Route::current()->uri() === 'frogger' )
		<li class="active">Frogger</li>
	@else
		<li><a href="/frogger">Frogger</a></li>
	@endif
@endpush

@section('body')
<body id="frogger-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection