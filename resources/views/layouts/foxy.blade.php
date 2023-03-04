@extends('layouts.master')

@include('nav.foxy')

@include('css.main')
@include('css.google-material-icons')
@include('css.bootstrap5')

@include('scripts.bootstrap5')

@section('body')
    <body id="foxy-body">
    @include('header')
    @yield('body-content')
    @stack('scripts')
    </body>
@endsection
