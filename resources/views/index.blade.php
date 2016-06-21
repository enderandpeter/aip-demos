@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/index.css" />
@endpush

@include('scripts.jquery')
@include('scripts.bootstrap')

@section('body-content')
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
		<li><a href="search-my-backyard">Search my Backyard!</a> — <a href="https://www.udacity.com" target="_blank">Udacity</a> — <a href="https://www.udacity.com/course/javascript-design-patterns--ud989" target="_blank">JavaScript Design Patterns</a>  final project</li>
		<li><a href="frogger">Effective JavaScript: Frogger</a> — <a href="https://www.udacity.com" target="_blank">Udacity</a> — <a href="https://www.udacity.com/course/object-oriented-javascript--ud015" target="_blank">Object-Oriented JavaScript</a>  final project</li>
		<li><a href="{{ env('BRL_URL', 'http://brl.aninternetpresence.net') }}" target="_blank">Booj Reading List</a> — <a href="http://booj.com/" target="_blank">Booj</a> — <a href="https://github.com/enderandpeter/boojbooks" target="_blank">Application Code Test</a></li>
		<li><a href="event-planner">Event Planner</a> — <a href="https://www.udacity.com" target="_blank">Udacity</a> — <a href="https://www.udacity.com/course/building-high-conversion-web-forms--ud890" target="_blank">Building High Conversion Web Forms</a>  final project</li>
	</ul>
@endsection