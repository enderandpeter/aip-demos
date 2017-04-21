@extends('layouts.master')

@push('nav-list-items')	
	@if ( Route::current()->uri() === 'search-my-backyard' )
		<li class="breadcrumb-item active">Search my Backyard</li>
	@else
		<li class="breadcrumb-item"><a href="/search-my-backyard">Search my Backyard</a></li>
	@endif
@endpush

@include('css.main')
@include('css.bootstrap')

@include('scripts.google-maps')
@include('scripts.bootstrap')
@include('scripts.ko')
@include('scripts.jquery')

@section('body')
<body id="search-my-backyard-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection