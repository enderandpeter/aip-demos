@extends('layouts.master')

@section('body')
<body id="search-my-backyard-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection