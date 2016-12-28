@extends('layouts.master')

@section('body')
<body id="frogger-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection