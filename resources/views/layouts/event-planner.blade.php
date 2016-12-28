@extends('layouts.master')

@section('body')
<body id="event-planner-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection