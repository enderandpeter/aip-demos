@extends('layouts.frogger')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/frogger/style.css" />
@endpush

@include('nav.demos')

@section('title')
Effective JavaScript: Frogger
@endsection

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
		        <p>This is the <a href="https://classroom.udacity.com/courses/ud015/lessons/3072058665/concepts/30962186380923" target="_blank">final project</a> for the <a href="https://www.udacity.com/course/object-oriented-javascript--ud015" target="_blank">Object-Oriented JavaScript Udacity course</a>. You can find other demonstrations on the <a href="/">Demo site.</a></p>
			    <h3>How to Play</h3>
			    <p>
			    	Choose your character with the Left and Right arrow keys. Press the Up or Down arrow key to select the character. The game is reminiscent of Frogger in that the directional arrows will
			    	move you around the map. 
			    </p>
			    	<div id="itemGrid" class="container-fluid">
			    		<div class="row">
			    			<div class="col-md-6 image-column">
			    				<img class="itemImage" src="/resources/frogger/images/Gem Blue.png">
			    			</div>			    					    		
			    			<div class="col-md-6 description">100 points</div>
			    		</div>
			    		<div class="row">
			    			<div class="col-md-6 image-column">
			    				<img class="itemImage" src="/resources/frogger/images/Gem Green.png">
			    			</div>			    		
			    			<div class="col-md-6 description">200 points</div>
			    		</div>
			    		<div class="row">
			    			<div class="col-md-6 image-column">
			    				<img class="itemImage" src="/resources/frogger/images/Gem Orange.png">
			    			</div>
			    			<div class="col-md-6 description">300 points</div>
			    		</div>			
			    		<div class="row">
			    			<div class="col-md-6 image-column">
			    				<img class="itemImage" src="/resources/frogger/images/Heart.png">
			    			</div>
			    			<div class="col-md-6 description">300 points, Extra Life</div>
			    		</div>
			    		<div class="row">
			    			<div class="col-md-6 image-column">
			    				<img class="itemImage" src="/resources/frogger/images/Rock.png">
			    			</div>
			    			<div class="col-md-6 description">An impassable structure</div>
			    		</div>
			    	</div>
			    <p>
			    	If a bug hits your character, you will loose a life and 500 points. If you loose more than three lives or get a score under zero, the game ends.
			    </p>
			    <h2>Credits</h2>
			    <p>
			    	<div class="creditLine">
			    		<strong>Created by: </strong> Spencer Williams IV
			    	</div>
			    	<div class="creditLine">
			    		<strong>Music: </strong> <a href="http://freesound.org/people/axtoncrolley/sounds/172707/" target="_blank">Nodens (Field Song) by axtoncrolley</a>
			    	</div>
			    	<div class="creditLine">
			    		<strong>Sounds: </strong> Created with <a href="http://www.bfxr.net/" target="_blank">Bxfr</a>
			    	</div>
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
	  <div id="musicControlContainer" class="audioControl">
	  	<label for="musicControl">Music</label>
	    <input type="checkbox" id="musicControl" value="Music" data-bind="checked: gameSettings.musicOn(), click: gameSettings.toggleMusic">
	  </div>
	  <div id="soundControlContainer" class="audioControl">
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
		<ul id="playerSelectList" style="right: 7px;" data-bind="css: { active: active }, foreach: characters">
			<li class="playerBox" data-bind="css: { selected: selected() }, attr: { id: id}">
				<h2 data-bind="text: name"></h2>
			</li>
		</ul>
	</div>
@endsection