@extends('layouts.master')

@section('body')
<body id="index-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection