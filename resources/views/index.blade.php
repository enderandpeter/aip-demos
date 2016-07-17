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
	<dl class="dl-horizontal">
		<dt><a target="_blank" href="https://www.udacity.com">Udacity</a></dt>
			<dd><a href="search-my-backyard">Search my Backyard!</a> &mdash; <a target="_blank" href="https://www.udacity.com/course/javascript-design-patterns--ud989">JavaScript Design Patterns</a>  final project</dd>
			<dd><a href="frogger">Effective JavaScript: Frogger</a> &mdash; <a target="_blank" href="https://www.udacity.com/course/object-oriented-javascript--ud015">Object-Oriented JavaScript</a>  final project</dd>
		<dt><a target="_blank" href="http://booj.com/">Booj</a></dt>
			<dd><a target="_blank" href="{{ env( 'BRL_URL', 'http://brl.aninternetpresence.net') }}">Booj Reading List</a> &mdash; <a target="_blank" href="https://github.com/enderandpeter/boojbooks">Application Code Test</a></dd>
		<dt><a href="https://www.blender.org/" target="_blank">Blender</a></dt>
			<dd><a href="resources/yellow-submarine/videos/my_yellow_submarine.mp4">Yellow Submarine</a> (<a href="resources/yellow-submarine/models/YellowSub.blend">My first 3D model!</a>) &mdash; <a href="http://gryllus.net/Blender/3D.html" target="_blank">Neal Hirsig's Blender 3D Design Course</a> &mdash; <a href="http://gryllus.net/Blender/PDFTutorials/01AYellowSubmarine_iTunesU/YellowSubmarine.pdf" target="_blank">Yellow Submarine tutorial</a></dd>
			<dd><a href="resources/castle/Castle_Texture.png">Castle (Textured)</a> (<a href="resources/castle/models/Castle_Texture.blend">2nd 3D model</a>) &mdash; <a href="http://gryllus.net/Blender/3D.html" target="_blank">Neal Hirsig's Blender 3D Design Course</a> &mdash; <a href="http://gryllus.net/Blender/PDFTutorials/02BCastleTexturing_ITunesU/CastleTexturing.pdf" target="_blank">Castle Texturing tutorial</a></dd>
	</dl>\
@endsection