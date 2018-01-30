@extends('layouts.flappy-bird')

@push('css')
	<link rel="stylesheet" type="text/css" href="css/flappy-bird/main.css" />
@endpush

@include('nav.demos')

@section('title')
Yet Another Flappy Bird Clone
@endsection

@section('body-content')
	<div id="main-container" class="container-fluid text-center">
		<img id="intro-img" src="resources/flappy-bird/flappy-bird-intro.png" alt="Game screenshot" class="img-fluid" />
		<h1 class="mt-4">Yet Another Flappy Bird Clone</h1>
    		<p>Another essential <a href="https://www.udemy.com/make-mobile-games-like-a-pro-using-unity-game-engine/" target="_blank">Udemy course project</a>.
    		This one does a much better job accomodating to a variety of screen resolutions. A mouse or touch screen is required.
    		</p>
    		<p>Builds are avalailable for <a href="https://github.com/enderandpeter/flappy-bird/releases/download/v1.1/Yet.Another.Flappy.Bird.Clone-v1.1-win.zip">Windows</a> and <a href="https://github.com/enderandpeter/flappy-bird/releases/download/v1.1/Yet.Another.Flappy.Bird.Clone-v1.1-mac.zip">macOS</a>.
    		An HTML5 and Linux version will be available soon.</p>
    		<p>For the macOS version, I cannot explain why the app folder icon looks like some weird mosaic. Warhol-esque, almost.</p>
    		<ul id="build-list" class="list-group mx-auto mb-4">
          <li class="list-group-item">
          	<a class="download-link" href="https://github.com/enderandpeter/flappy-bird/releases/download/v1.1/Yet.Another.Flappy.Bird.Clone-v1.1-win.zip">
          		<img src="resources/software/windows.svg" class="download-icon"/>
          		<span class="download-link">Download for Windows</span>
          	</a>
          </li>
          <li class="list-group-item">
          	<a class="download-link" href="https://github.com/enderandpeter/flappy-bird/releases/download/v1.1/Yet.Another.Flappy.Bird.Clone-v1.1-mac.zip">
          		<img src="resources/software/apple.svg" class="download-icon"/>
          		<span class="download-link">Download for macOS</span>
          	</a>
          </li>
        </ul>
        <small class="mb-4 d-block"">
        		<div>Icons made by <a href="http://www.freepik.com" target="_blank" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        		<div>Icons made by <a href="https://www.flaticon.com/authors/dave-gandy" target="_blank" title="Dave Gandy">Dave Gandy</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        </small>
	</div>
@endsection