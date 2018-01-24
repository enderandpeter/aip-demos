@extends('layouts.master')

@include('nav.jack-the-giant')
@include('nav.jack-the-giant-player')

@include('css.main')
@include('css.bootstrap4')

@include('scripts.jack-the-giant-player')
@include('scripts.bootstrap4')
@include('scripts.ko')
@include('scripts.jquery')

@section('body')
<body id="jack-the-giant-player-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection