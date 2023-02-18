@extends('layouts.master')

@push('nav-list-items')
	@if ( Route::current()->uri() === 'search-my-backyard' )
		<li class="breadcrumb-item active">Search my Backyard</li>
	@else
		<li class="breadcrumb-item"><a href="/search-my-backyard">Search my Backyard</a></li>
	@endif
@endpush

@include('css.search-my-backyard')
@include('css.google-material-icons')
@include('css.bootstrap4')

@include('scripts.google-maps')
@include('scripts.bootstrap4')

@section('body')
<body id="search-my-backyard-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection
