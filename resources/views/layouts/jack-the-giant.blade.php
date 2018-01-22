@extends('layouts.master')

@push('nav-list-items')
	@if ( Route::current()->uri() === 'jack-the-giant' )
		<li class="breadcrumb-item active">Jack the Giant</li>
	@else
		<li class="breadcrumb-item"><a href="/jack-the-giant">Jack the Giant</a></li>
	@endif
@endpush

@include('css.main')
@include('css.bootstrap4')

@include('scripts.bootstrap4')
@include('scripts.ko')
@include('scripts.jquery')

@section('body')
<body id="jack-the-giant-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection