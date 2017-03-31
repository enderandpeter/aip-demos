@extends('layouts.master')

@push('nav-list-items')	
	@if ( Route::current()->uri() === 'search-my-backyard' )
		<li class="active">Search my Backyard</li>
	@else
		<li><a href="/search-my-backyard">Search my Backyard</a></li>
	@endif
@endpush

@section('body')
<body id="search-my-backyard-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection