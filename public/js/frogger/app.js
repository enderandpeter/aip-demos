/**
 * 
 * Make a new SoundContrller
 * 
 * @constructor
 * @this SoundController
 * @classdesc Much borrowed from {@link https://www.udacity.com/course/cs255 Google's Udacity HTML 5 Game Development course} 
*/
function SoundController(){
	/**
	 * The sound clips to be controlled
	 * @member {object}
	 */
	this.clips = {};
	/**
	 * Whether or not this controller is enabled
	 * @member {boolean} 
	 */
	this.enabled = true;
	/**
	 * Represents all aspects of the sound played
	 * @member {AudioContext}
	 */
	this.context = null;
	/**
	 * Used to control volume
	 * @member {GainNode}
	 */
	this.mainNode = null;
}

/**
 * Prepare the audio context
 * @this SoundController
 * @function SoundController#init
 */
SoundController.prototype.init = function(){
	window.AudioContext = window.AudioContext || window.webkitAudioContext;
	this.context = new AudioContext();;
	this.mainNode = this.context.createGain();
	this.mainNode.connect(this.context.destination);
}

/**
 * Load the sound to be played
 * 
 * @this SoundController
 * @function SoundController#load
 * @param {string} path The location of the sound
 * @param {function} callback The action to be performed on the sound
 * @returns {Sound}
 */
SoundController.prototype.load = function(path, callback){
	var thisSC = this;
	if(!this.context){
		this.init();
	}
	
	if(sc.clips[path]){
		callback(sc.clips[path].sound);
		return sc.clips[path].sound;
	}
	
	var clip = {
		sound: new Sound(),
		buffer: null,
		loop: false
	};
	sc.clips[path] = clip;
	clip.sound.path = path;
	
	var request = new XMLHttpRequest();
	request.open('GET', path, true);
	request.responseType = 'arraybuffer';
	request.onload = function () {
		thisSC.context.decodeAudioData(request.response, function(buffer){
			thisSC.clips[path].buffer = buffer;
			callback(sc.clips[path].sound);		
		});
	};

	request.send();
	
	return clip.sound;
};

/**
 * Actually play the sound
 * @this SoundController
 * @function SoundController#playSound
 * @param {string} path The path to the sound
 * @param {object} settings An object with properties set to the sound's settings
 * @returns {boolean}
 */
SoundController.prototype.playSound = function(path, settings){
	if(!sc.enabled) return false;
	
	var looping = false;
	var volume = 1;
	
	if (settings) {
		if (settings.looping) looping = settings.looping;
		if (settings.volume) volume = settings.volume;
	}
	
	var sd = this.clips[path];
	if (sd === null) return false;

	var currentClip = null;
	
	currentClip = this.context.createBufferSource();	
	currentClip.buffer = sd.buffer;
	this.mainNode.gain.value = volume;
	currentClip.loop = looping;
	
	currentClip.connect(this.mainNode);
	currentClip.start = currentClip.start || currentClip.noteOn;
	currentClip.start(0);	
};

/**
 * Get ready to play the sound at the passed in path
 * 
 * @param {string} path The location of the sound
 * @param {boolean} loop Whether or not to loop the sound
 * @this SoundController
 * @function SoundController#play
 */
SoundController.prototype.play = function(path, loop){
	return sc.load(path, function(sObj){
		sObj.play(loop);
	});
};

/**
 * Mute or unmute the sound
 * 
 * @param {boolean} mute If true, then mute the sound
 * @this SoundController
 * @function SoundController#mute
 */
SoundController.prototype.mute = function(mute){
	if(this.mainNode.gain.value > 0 || mute) {
		this.mainNode.gain.value = 0;
	}
	else {
		this.mainNode.gain.value = 1;
	}
};

/**
 * Stop all sounds
 * 
 * @this SoundController
 * @function SoundController#stopAll
 */
SoundController.prototype.stopAll = function(){
	this.mainNode.disconnect();
	this.mainNode = this.context.createGain(0);
	this.mainNode.connect(this.context.destination);
};

/**
 *  Create a single sound
 * 
 * @constructor
 * @this Sound
 * @classdesc A single sound
 */
function Sound(){
	/** The file path to this sound 
	 * @member {string}
	 * */
	this.path = '';
}

/**
 * Play this sound 
 * 
 * @param {boolean} loop Whether or not the sound will repeat indefinitely
 * @this Sound
 * @function Sound#play
 */
Sound.prototype.play = function(loop){
	var settings = {volume: 1, looping: loop};
	sc.playSound(this.path, settings);
};

/**
 * Enemies our player must avoid
 */
var Enemy = function() {
    // Variables applied to each of our instances go here,
    // we've provided one for you to get started

    // The image/sprite for our enemies, this uses
    // a helper we've provided to easily load images
    this.sprite = 'resources/frogger/images/enemy-bug.png';
	this.width = 101;
	this.height = 75;
	this.pointDamage = 500;
	
	// Initial location
	this.x = -this.width;
	this.y = (this.height - 2) * Math.floor((Math.random() * 3) + 1);
	// Initial speed
	this.speed = 1;
	this.speed = (Math.random() + 1) * this.speed * 200;
};

/**
 * Update the enemy's position, required method for game
 * 
 * @param {Number} dt a time delta between ticks 
 */
Enemy.prototype.update = function(dt) {
    // You should multiply any movement by the dt parameter
    // which will ensure the game runs at the same speed for
    // all computers.
	this.x += this.speed * dt;
	
	var collisionOffset = 40;
	
	// Handle Collisions
	if((player.x > this.x - collisionOffset && player.x < this.x - collisionOffset + this.width) && (player.y > this.y - collisionOffset && player.y < this.height + this.y - collisionOffset)){
		player.score(player.score() - this.pointDamage);
		player.changeHealth(-1);
		if(gameData.gameSettings.soundOn()){
			sc.play('resources/frogger/sounds/hit.wav');
		}		
		player.reset();
	}
	
	if(this.x > ctx.canvas.width + this.width){
		allEnemies.splice(allEnemies.indexOf(this), 1);
	}
};

/**
 * Draw the enemy on the screen, required method for game
 */
Enemy.prototype.render = function() {
    ctx.drawImage(Resources.get(this.sprite), this.x, this.y);
};


// Now write your own player class
// This class requires an update(), render() and
// a handleInput() method.

/**
 * A player the user controls.
 * 
 * @class
 */
var Player = function(){
	// Load the image
	this.sprite = 'resources/frogger/images/char-boy.png';
	this.width = 101;
	this.height = 75;
	this.verticalOffset = 15;
	this.score = ko.observable(0);	
	
	// Initial location
	this.reset();
	this.speed = 1;
};

Player.maxLives = 3;

/**
 * Reset the player's position and set current score
 * 
 */
Player.prototype.reset = function(){	
	// Initial location
	this.x = this.width * 2;
	this.y = this.height * 6 - 50;
};

Player.prototype.changeHealth = function(change){
	var livespanel = document.querySelector('#livespanel');
	
	if(change > 0){
		for(var i = 0; i < change; i++){
			var livesListItems = document.querySelectorAll('#livespanel li');
			if(livesListItems.length === Player.maxLives){
				return;
			}
			
			var lifeListItem = document.createElement('li');
			lifeListItem.className = 'playerLife';
			lifeListItem.style.backgroundImage = 'url("' + this.sprite; '")';
			livespanel.appendChild(lifeListItem);
		}
	} else {
		change *= -1;
		for(var i = 0; i < change; i++){
			if(livespanel.firstElementChild){
				livespanel.removeChild(livespanel.firstElementChild);
			} else {
				endGame();
			}
		}		
	}
}

Player.prototype.resetLives = function(){
	var livespanel = document.querySelector('#livespanel');
	
	while(livespanel.firstChild){
		livespanel.removeChild(livespanel.firstChild);
	}
	
	for(var i = 0; i < Player.maxLives; i++){
		var lifeListItem = document.createElement('li');
		lifeListItem.className = 'playerLife';
		lifeListItem.style.backgroundImage = 'url("' + this.sprite; '")';
		livespanel.appendChild(lifeListItem);
	}
}

/**
 * Update the player's state
 */
Player.prototype.update = function() {
	// Raise the score if the player reached the water
	if(this.y <= this.verticalOffset){
		this.score(this.score() + 100);
		resetMap();
	}
	
	// End the game if the player's points are less than zero
	if(player.score() < 0 && gameData.gameState() !== 'character-select' && gameData.gameState() !== 'ended'){
		endGame();
	}
}

/**
 * Get the player's x position on the sidewalk grid
 * @returns {Number} The x grid coordinate on the sidewalk
 */
Player.prototype.getGridX = function(){
	return Math.round(this.x / this.width);
}

/**
 * Get the player's y position on the sidewalk grid
 * @returns {Number} The y grid coordinate on the sidewalk	
 */
Player.prototype.getGridY = function(){
	return Math.round(this.y / this.height);
}

/**
 * The previous x position the player was at
 */
Player.prototype.prevX = null;

/**
 * The previous y position the player was at
 */
Player.prototype.prevY = null;

// Now instantiate your objects.
// Place all enemy objects in an array called allEnemies
// Place the player object in a variable called player

/**
 * Handle the user's input to control the character
 */
Player.prototype.handleInput = function(input){	
	var playerSelectListView = document.querySelector('#playerSelectList');
	
	if(gameData.gameState() === 'ended'){
		return;
	}
	
	var position = null;
	
	this.prevX = this.x;
	this.prevY = this.y;
	
	switch(input){		
		case 'left':
			if(gameData.gameState() === 'character-select'){ // Choose character
				if(gameData.gameSettings.soundOn()){
					sc.play('resources/frogger/sounds/menu_nav.wav');
				}					
				var selectedCharacter = document.querySelector('.playerBox.selected');
				if(selectedCharacter.previousElementSibling){
					var newSelection = selectedCharacter.previousElementSibling;
					var offsetLeft = selectedCharacter.previousElementSibling.offsetLeft;
					playerSelectListView.style.right = offsetLeft - 50 + 'px';
					playerSelectList.selectCharacter(newSelection);
				}
				return;
			}
			if(gameData.gameSettings.soundOn()){
				sc.play('resources/frogger/sounds/move.wav');
			}			
			var position = this.x - this.width;
			if(position >= 0){
				this.x = position;
				this.update();
			}
		break;
		case 'right':
			if(gameData.gameState() === 'character-select'){  // Choose character
				if(gameData.gameSettings.soundOn()){
					sc.play('resources/frogger/sounds/menu_nav.wav');
				}
				var selectedCharacter = document.querySelector('.playerBox.selected');
				if(selectedCharacter.nextElementSibling){
					var newSelection = selectedCharacter.nextElementSibling;
					var offsetLeft = selectedCharacter.nextElementSibling.offsetLeft;
					playerSelectListView.style.right = offsetLeft - 50 + 'px';		
					playerSelectList.selectCharacter(newSelection);
				}
				return;
			}
			if(gameData.gameSettings.soundOn()){
				sc.play('resources/frogger/sounds/move.wav');
			}			
			var position = this.x + this.width;
			if(position < ctx.canvas.width){
				this.x = position;
				this.update();
			}
		break;
		case 'up':
			if(gameData.gameState() === 'character-select'){ // Select character
				gameData.gameState('in-level');
				startGame();
				return;
			}
			if(gameData.gameSettings.soundOn()){
				sc.play('resources/frogger/sounds/move.wav');
			}
			var position = this.y - this.height - this.verticalOffset;
			if(position > -this.height){ // Allow player to potentially stand in water
				this.y = position;
				this.update();
			}
		break;
		case 'down':
			if(gameData.gameState() === 'character-select'){  // Select character
				gameData.gameState('in-level');
				startGame();
				return;
			}
			if(gameData.gameSettings.soundOn()){
				sc.play('resources/frogger/sounds/move.wav');
			}
			var position = this.y + this.height + this.verticalOffset;
			if(position < ctx.canvas.height -  2 * this.height){
				this.y = position;
				this.update();
			}
		break;
	}
	
	if(this.y === 0){
		resetMap();
	}
};

/**
 * Draw the player on the canvas
 */
Player.prototype.render = function() {
    ctx.drawImage(Resources.get(this.sprite), this.x, this.y);
};

/**
 * An item on the map.
 * 
 * Items can be either Structures that cannot picked up but may allow some interaction, or
 * Pickups which are consumed by the user when touched. 
 */
var Item = function(){
	this.sprite = null;
	
	this.x = this.width * this.getGridX();
	this.y = this.height * this.getGridY();
	
	this.getSprite();
}

Item.prototype.getSprite = function(){
	var spriteIndex = Math.round(Math.random() * (this.spriteList.length - 1));
	this.sprite = this.spriteList[spriteIndex];	
};

/**
 * Draw the item on the canvas
 */
Item.prototype.render = function(){
	ctx.drawImage(Resources.get(this.sprite), this.x, this.y - 50);
}

/**
 * Initialize the item's x coordinate on the sidewalk grid
 * @returns {Number} The item's x coordinate on the sidewalk grid
 */
Item.prototype.getGridX = function(){
	this.gridX = Math.floor(Math.random() * 5); 
	return this.gridX;
}

/**
 * Initialize the item's y coordinate on the sidewalk grid
 * @returns {Number} The item's y coordinate on the sidewalk grid
 */
Item.prototype.getGridY = function (){
	this.gridY = Math.floor((Math.random() * 3) + 1); 
	return this.gridY;
}

/**
 * The item's x coordinate on the sidewalk grid
 * @member {Number}
 */
Item.prototype.gridX = null;
/**
 * The item's y coordinate on the sidewalk grid
 * @member {Number}
 */
Item.prototype.gridY = null;

/**
 * The list of sprites representing this item.
 */
Item.prototype.spriteList = [];

/**
 * An immobile structure on the map.
 * 
 * This is an Item that is typically considered some physical part of the map. They may allow some
 * interaction, such as moving or consuming, but some such as Rocks are just there as obstacles.
 * 
 * @class
 */
var Structure = function(){
	Item.call(this);
}
Structure.prototype = Object.create(Item.prototype);
Structure.prototype.constructor = Structure;

/**
 * A rock obstacle
 * 
 * This kind of Structure is an immobile one that makes the user choose other paths.
 * 
 * @class
 */
var Rock = function(){	
	this.spriteList = ['resources/frogger/images/Rock.png'];
	this.width = 101;
	this.height = 95;	
	Structure.call(this);
	
}
Rock.prototype = Object.create(Structure.prototype);
Rock.prototype.constructor = Rock;

/**
 * Update when touching a rock
 */
Rock.prototype.update = function(){	
	// Don't let the player move past a structure
	if(this.gridX === player.getGridX() && this.gridY === player.getGridY()){
		if(player.prevX !== null){
			player.x = player.prevX;			
		}
		if(player.prevY !== null){
			player.y = player.prevY;
		}
	}
};

/**
 * An Item that can consumed by a player
 * 
 * Pickups are Items that the user touches and immediately uses. They typically have beneficial properties,
 * but that is not a requirement.
 * 
 * @class
 */
var Pickup = function(){
	Item.call(this);
}
Pickup.prototype = Object.create(Item.prototype);
Pickup.prototype.constructor = Pickup;
Pickup.prototype.points = 0;

/**
 * Detect collisions with players
 */
Pickup.prototype.update = function(){	
	// Handle Collisions
	if(this.gridX === player.getGridX() && this.gridY === player.getGridY()){
		if(this.points){
			player.score(player.score() + this.points);
		}
		
		gameData.allItems.splice(gameData.allItems.indexOf(this), 1);	
	}	
};

/**
 * A gem pickup
 * 
 * A user can pick up a gem for extra points
 */
var Gem = function(){	
	this.width = 101;
	this.height = 95;

	this.spriteList = [
	   'resources/frogger/images/Gem Blue.png',
	   'resources/frogger/images/Gem Green.png',
	   'resources/frogger/images/Gem Orange.png'
	];
	
	Pickup.call(this);
	
	this.points = 100 * (this.spriteList.indexOf(this.sprite) + 1);
}
Gem.prototype = Object.create(Pickup.prototype);
Gem.prototype.constructor = Gem;

/**
 * Update after picking up a gem
 */
Gem.prototype.update = function(){
	Pickup.prototype.update.call(this);
	
	if(this.gridX === player.getGridX() && this.gridY === player.getGridY()){
		if(gameData.gameSettings.soundOn()){
			sc.play('resources/frogger/sounds/gem.wav');
		}
	}
};

/**
 * A heart pickup
 * 
 * Restores health and adds points.
 */
var Heart = function(){
	this.width = 101;
	this.height = 120;
	
	this.spriteList = [
	  'resources/frogger/images/Heart.png'
	];
	
	Pickup.call(this);
	
	this.x = (this.width) * this.getGridX();
	this.y = (this.height - 30) * this.getGridY();
	
	this.points = 300;
};
Heart.prototype = Object.create(Pickup.prototype);
Heart.prototype.constructor = Heart;

/**
 * Update after picking up a heart
 */
Heart.prototype.update = function(){
	Pickup.prototype.update.call(this);
	
	if(this.gridX === player.getGridX() && this.gridY === player.getGridY()){
		if(gameData.gameSettings.soundOn()){
			sc.play('resources/frogger/sounds/heart.wav');
		}		
		player.changeHealth(1);	
	}
};

/**
 * Draw the heart on the canvas
 */
Heart.prototype.render = function(){
	ctx.drawImage(Resources.get(this.sprite), this.x, this.y - 20);
}

/**
 * A list of players to start the game with
 */
var PlayerSelectList = function(){
	this.characters = ko.observableArray([]);
	this.active = ko.observable(false);
	this.selectCharacter = function(li){
		var characterIndex = Array.prototype.indexOf.call(document.querySelectorAll('.playerBox'), li);
		this.characters().map(function(character){
			character.selected(false);
		});
		this.characters()[characterIndex].selected(true);
	};
	
	/*
	 * An initial list of characters from which the main characters array is created
	 */
	var characterList = {
		'boy': 'Boy',
		'cat-girl': 'Cat Girl',
		'horn-girl': 'Horn Girl',
		'pink-girl': 'Pink Girl',
		'princess-girl': 'Princess'
	};
	
	for(var characterId in characterList){
		var characterName = characterList[characterId];
		this.characters.push({
			name: characterName,
			id: characterId,
			imageUrl: 'resources/frogger/images/char-' + characterId + '.png',
			selected: ko.observable(false)
		});
	}
};

var player = null;
var playerSelectList = new PlayerSelectList;
var allEnemies = [];
var gameData = {
	allItems : [],
	gameState: ko.observable('character-select'), // in-level, ended, character-select
	gameSettings: {
		musicOn: ko.observable(true),
		soundOn: ko.observable(true),
		toggleMusic: function(data, event){
			if(gameData.gameSettings.musicOn()){
				sc.stopAll();
				gameData.gameSettings.musicOn(false);
			} else {
				sc.play('resources/frogger/sounds/music.mp3', true);
				gameData.gameSettings.musicOn(true);
			}
			return true;
		},
		toggleSound: function(data, event){
			gameData.gameSettings.soundOn(!gameData.gameSettings.soundOn());
			return true;
		}
	}
}
ko.applyBindings(playerSelectList, document.querySelector('#playerSelectList'));
var sc = new SoundController;
intializeMap();

/**
 * Initialize the map
 */
function intializeMap(){
	player = new Player;
	ko.applyBindings(gameData, document.querySelector('#canvasOverlay'))
	ko.applyBindings(gameData, document.querySelector('#playerSelectCursor'));
	ko.applyBindings(gameData, document.querySelector('#volumeControls'));
	ko.applyBindings(player, document.querySelector('#dashboard'));
	startGame();
}

/**
 * Reset the map
 */
function resetMap(){
	resetItems();
	player.reset();
}

/**
 * Draw all map items on the canvas
 */
function drawItems(){
	if(gameData && gameData.allItems){
		var newItemCount;
		for(newItemCount = 0; newItemCount < 6; newItemCount++){
			var newItem;			
			var itemInPosition = true;			
			while(itemInPosition){
				var hasHeart = gameData.allItems.some(function(item){
					return item instanceof Heart; 
				});
				
				var hasRock = gameData.allItems.some(function(item){
					return item instanceof Rock; 
				});
				
				if(!hasHeart){
					newItem = new Heart;
				} else if(!hasRock) {
					newItem = new Rock;
				} else {
					newItem = new Gem;
				}
				
				itemInPosition = gameData.allItems.some(function(item){
					return item.gridX === newItem.gridX && item.gridY === newItem.gridY; 
				});
			}
			
			gameData.allItems.push(newItem);
		}
	}	
};

/**
 * Clear the list of map items and remake them.
 */
function resetItems(){
	if(gameData && gameData.allItems){
		gameData.allItems = [];
		drawItems();
	}
}

/**
 * End the game and show the Game Over messages
 */
function endGame(){
	sc.stopAll();
	var playerSelectListView = document.querySelector('#playerSelectList');
	var playerSelectListContainer = document.querySelector('#playerSelectListContainer');
	playerSelectListContainer.appendChild(playerSelectListView);
	
	var canvasOverlay = document.querySelector('#canvasOverlay');
	var main_caption = document.querySelector('#main_caption');
	main_caption.textContent = 'Game Over';
	
	var emptySpan = document.querySelector('#canvasOverlay span.empty');
	
	var bottom_caption = document.querySelector('#bottom_caption');
	bottom_caption.textContent = 'Press any key to continue';
	canvasOverlay.className = 'gameover';
	
	gameData.gameState('ended');
	canvasOverlay.addEventListener('animationend', function(){
		document.addEventListener('keyup', restartOnKeyUp);
	});
}

function restartOnKeyUp(event){
	event.preventDefault();
	if(gameData.gameState() === 'ended'){
		gameData.gameState('character-select');
		startGame();
	}
}

/**
 * Start the game
 */
function startGame(){
	document.removeEventListener('keyup', restartOnKeyUp);
	var canvasOverlay = document.querySelector('#canvasOverlay');
	var main_caption = document.querySelector('#main_caption');
	var playerSelectListView = document.querySelector('#playerSelectList');
	var playerSelectListContainer = document.querySelector('#playerSelectListContainer');
	
	// Clear the canvas overlay's main caption
	while(main_caption.firstChild){
		main_caption.removeChild(main_caption.firstChild);
	}
	
	var selectedCharacterData = playerSelectList.characters().filter(function(element){
		return element.selected();
	});
	
	if(gameData.gameState() === 'ended' || gameData.gameState() === 'character-select'){		
		// Start character selection
		gameData.gameState('character-select');
		if(gameData.gameSettings.musicOn()){
			sc.play('resources/frogger/sounds/music.mp3', true);
		}
		main_caption.appendChild(playerSelectListView);
		
		if(!selectedCharacterData.length){
			playerSelectList.characters()[0].selected(true);
		}
		
		playerSelectList.active(true);
		return;
	}
	
	playerSelectListContainer.appendChild(playerSelectListView);
	gameData.gameState('in-level');
	
	var selectedCharacterData = playerSelectList.characters().filter(function(element){
		return element.selected();
	});
	
	player.sprite = selectedCharacterData[0].imageUrl; 
	player.score(0);
	
	// Reset the player's lives
	player.resetLives();
	resetMap();
}

/*
 * Push enemies onto map every second. Keep a maximum of enemies.
 */
window.setInterval(function(){	
	if(allEnemies.length < 6){
		allEnemies.push(new Enemy);
	}	
}, 1000);

// This listens for key presses and sends the keys to your
// Player.handleInput() method. You don't need to modify this.
document.addEventListener('keyup', function(e) {    
	var allowedKeys = {
        37: 'left',
        38: 'up',
        39: 'right',
        40: 'down'
    };

    player.handleInput(allowedKeys[e.keyCode], e);
   
    e.preventDefault();
    return false;
});
