@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/index.css" />
@endpush

@include('scripts.jquery')
@include('scripts.bootstrap')

@section('body-content')
	<ol class="breadcrumb">
	  <li><a href="{{ env('MAIN_URL', 'http://aninternetpresence.net') }}" target="_blank">An Internet Presence</a></li>
	  <li class="active">Demos</li>
	</ol>
	<div class="page-header">
	  <h1>An Internet Presence - Demos</h1>
	</div>
	<div class="well intro">Welcome to the Demo site. Here you will find a handful of interesting web exhibitions made by yours truly. 
	Inspiration comes from many places: tools of personal necessity, requested applications, projects from online learning sites, spontaneous sparks 
	of creativity, etc. If you have any comments, just let me know at my main site.</div>
	<div class="page-header">
		<h2>Demos</h2>
	</div>
	<ul>
		<li><a href="search-my-backyard">Search my Backyard!</a> — <a href="https://www.udacity.com">Udacity</a> — <a href="https://www.udacity.com/course/javascript-design-patterns--ud989" target="_blank">JavaScript Design Patterns</a>  final project</li>
		<li><a href="frogger">Effective JavaScript: Frogger</a> — <a href="https://www.udacity.com">Udacity</a> — <a href="https://www.udacity.com/course/object-oriented-javascript--ud015" target="_blank">Object-Oriented JavaScript</a>  final project</li>
	</ul>
@endsection