@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/frogger/style.css" />
@endpush

{{-- These are being manually added because including the template with @push commands will include them after these instead of before --}}
@push ('scripts')	
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.0/knockout-min.js"></script>
	<script src="js/frogger/main.js"></script>
    <script src="js/frogger/resources.js"></script>
    <script src="js/frogger/app.js"></script>
    <script src="js/frogger/engine.js"></script>
@endpush

@section('body-content')
	<div id="modalContainer">
		<div class="modal fade" id="aboutModal" tabindex="-1" role="dialog">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h2 class="modal-title">About this game</h2>
		      </div>
		      <div class="modal-body">
		        <p>This is the <a href="https://classroom.udacity.com/courses/ud015/lessons/3072058665/concepts/30962186380923" target="_blank">final project</a> for the <a href="https://www.udacity.com/course/object-oriented-javascript--ud015">Object-Oriented JavaScript Udacity course</a>. You can find other demonstrations on the <a href="/">Demo site.</a></p>
			    <h3>How to Play</h3>
			    <p>
			    	Choose your character with the Left and Right arrow keys. Press the Up or Down arrow key to select the character. The game is reminiscent of Frogger in that the directional arrows will
			    	move you around the map. 
			    </p>
			    <p>
			    	The goal is to collect gems and hearts to score points and make it across the map to get more items. If a bug hits your character, you will loose a life and 500 points. If you loose more than three lives or get a score under zero, the game ends.
			    </p>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Got it!</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
	<h1>Effective JavaScript: Frogger</h1>
	<div id="canvasContainer">
	<div id="playerSelectCursor" data-bind="css: { active: gameState() === 'character-select' }"></div>
	<div id="volumeControls">    
	  <div id="musicControlContainer">
	  	<label for="musicControl">Music</label>
	    <input type="checkbox" id="musicControl" value="Music" data-bind="checked: gameSettings.musicOn(), click: gameSettings.toggleMusic">
	  </div>
	  <div>
	    <label for="soundControl">Sound</label>
	  	<input type="checkbox" id="soundControl" value="Sound" data-bind="checked: gameSettings.soundOn(), click: gameSettings.toggleSound">
	  </div>
	  <button type="button" id="aboutModalButton" class="btn btn-default btn-lg">
  		<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> About this game
	  </button>   
    </div>
	<div id="dashboard">
	  <ul id="livespanel">
	  </ul>
	  <div id="scoreboard">
	  	<span id="scorelabel">Score: </span>
	  	<div id="score" data-bind="text: score">1000</div>
	  </div>
	  
	</div>
		<div id="canvasOverlay" data-bind="css: {gameover: gameState() === 'ended', 'character-select': gameState() === 'character-select', active: gameState() !== 'in-level'}">
			<span class="empty"></span><div id="main_caption" data-bind="css: {playerSelect: gameState() === 'character-select'}">				
			</div>
			<div id="bottom_caption"></div>			
		</div>
	</div>
	
	<div id="playerSelectListContainer">
		<ul id="playerSelectList" style="right: 0px;" data-bind="css: { active: active }, foreach: characters">
			<li class="playerBox" data-bind="css: { selected: selected() }, attr: { id: id}">
				<h2 data-bind="text: name"></h2>
			</li>
		</ul>
	</div>
@endsection