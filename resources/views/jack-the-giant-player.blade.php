@extends('layouts.jack-the-giant-player')

@push('css')
	<link rel="stylesheet" type="text/css" href="css/jack-the-giant-player/main.css" />
@endpush

@include('nav.demos')

@section('title')
Jack the Giant
@endsection

@section('body-content')
	<div id="main-container" class="container-fluid text-center">
		<div class="webgl-content d-inline-block position-relative">
      		<div id="gameContainer"></div>
      		<img id="spinner" src="/resources/software/spinner.svg" class="position-absolute">
      	</div>
	</div>
@endsection