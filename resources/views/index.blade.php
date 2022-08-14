@extends('layouts.index')

@include('css.bootstrap4')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/home.css" />
@endpush

@include('scripts.bootstrap4')
@include('scripts.jquery')

@include('nav.demos')

@section('body-content')
	<div class="page-header">
	  <h1 class="ml-5 mb-3">An Internet Presence - Demos</h1>
	</div>
	<div class="intro">Welcome to the Demo site. Here you will find a handful of interesting web exhibitions made by yours truly.
	Inspiration comes from many places: tools of personal necessity, requested applications, projects from online learning sites, spontaneous sparks
	of creativity, etc. If you have any comments, just let me know at my main site.</div>
	<div class="page-header">
		<h2 class="ml-5 my-4">Demos</h2>
	</div>
	<dl id="demos-list" class="ml-5 mr-3">
        <div class="row">
            <dt class="col-3 col-sm-2">Projects</dt>
            <div class="col-8">
                <dd>
                    <a href="https://krackofdawnmobile.com" target="_blank">Krack of Dawn Mobile Kafe</a> - An online store for an excellent mobile food truck company in Austin, Texas
                </dd>
            </div>
        </div>
		<div class="row">
		<dt class="col-3 col-sm-2"><a target="_blank" href="https://www.udacity.com">Udacity</a></dt>
		<div class="col-8">
        		<dd><a href="search-my-backyard">Search my Backyard!</a> &mdash; <a target="_blank" href="https://www.udacity.com/course/javascript-design-patterns--ud989">JavaScript Design Patterns</a> final project</dd>
        		<dd><a href="frogger">Effective JavaScript: Frogger</a> &mdash; <a target="_blank" href="https://www.udacity.com/course/object-oriented-javascript--ud015">Object-Oriented JavaScript</a> final project</dd>
        		<dd><a href="event-planner">Event Planner</a> — <a href="https://www.udacity.com/course/building-high-conversion-web-forms--ud890" target="_blank">Building High Conversion Web Forms</a> final project</dd>
            	<dd><a href="{{ env('WYR_URL') }}" target="_blank">Would You Rather!</a> — A small example site for the <em>Would You Rather...</em> game for the <a href="https://www.udacity.com/course/react-nanodegree--nd019" target="_blank">React Nanodegree</a> second project</dd>
		</div>
		</div>
		<div class="row">
		<dt class="col-3 col-sm-2"><a href="https://www.blender.org/" target="_blank">Blender</a></dt>
		<div class="col-8">
        		<dd><a href="resources/yellow-submarine/videos/my_yellow_submarine.mp4">Yellow Submarine</a> (<a href="resources/yellow-submarine/models/YellowSub.blend">My first 3D model!</a>) &mdash; <a href="https://www.emg-mediamaker.com/tutorials-blender-3d-design.php" target="_blank">Neal Hirsig's Blender 3D Design Course</a> &mdash; <a href="http://gryllus.net/Blender/PDFTutorials/01AYellowSubmarine_iTunesU/YellowSubmarine.pdf" target="_blank">Yellow Submarine tutorial</a></dd>
        		<dd><a href="resources/castle/Castle_Texture.png">Castle (Textured)</a> (<a href="resources/castle/models/Castle_Texture.blend">2nd 3D model</a>) &mdash; <a href="https://www.emg-mediamaker.com/tutorials-blender-3d-design.php" target="_blank">Neal Hirsig's Blender 3D Design Course</a> &mdash; <a href="http://gryllus.net/Blender/PDFTutorials/02BCastleTexturing_ITunesU/CastleTexturing.pdf" target="_blank">Castle Texturing tutorial</a></dd>
		</div>
		</div>
		<div class="row">
		<dt class="col-3 col-sm-2"><a target="_blank" href="https://unity3d.com/">Unity</a></dt>
		<div class="col-8">
			<dd><a href="jack-the-giant">Jack the Giant</a> - A game from a Unity tutorial</dd>
			<dd><a href="flappy-bird">Yet Another Flappy Bird Clone</a> - Another game from a Unity tutorial</dd>
		</div>
		</div>
	</dl>
@endsection
