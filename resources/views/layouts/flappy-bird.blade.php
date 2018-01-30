@extends('layouts.master')

@include('nav.flappy-bird')

@push('meta')
<meta property="og:title" content="Yet Another Flappy Bird Clone" />
<meta property="og:url" content="https://demos.aninternetpresence.net/flappy-bird" />
<meta property="og:description" content="This one does a much better job accomodating to a variety of screen resolutions. A mouse or touch screen is required." />
<meta property="og:image" content="https://demos.aninternetpresence.net/resources/flappy-bird/flappy-bird-intro.png" />
@endpush

@include('css.main')
@include('css.google-material-icons')
@include('css.bootstrap4')

@include('scripts.flappy-bird')
@include('scripts.bootstrap4')
@include('scripts.ko')
@include('scripts.jquery')

@section('body')
<body id="flappy-bird-body">
	@include('header')
	@yield('body-content')
	@stack('scripts')
</body>
@endsection
