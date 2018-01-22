@extends('layouts.jack-the-giant')

@push('css')
	<link rel="stylesheet" type="text/css" href="css/jack-the-giant/main.css" />
@endpush

@include('nav.demos')

@section('title')
Jack the Giant
@endsection

@section('body-content')
	<div id="main-container" class="container-fluid text-center">
		<img src="resources/jack-the-giant/jack-the-giant-intro.png" alt="Jack the Giant" class="img-fluid" />
		<h2>My second collaborative game</h2>
		<p>My first was a game called Torch for <a href="http://www.grandschemegames.com/" target="_blank">Grand Scheme Games</a>.
    		This one, however, was created for a <a href="https://www.udemy.com/make-mobile-games-like-a-pro-using-unity-game-engine/" target="_blank">tutorial on Udemy</a> which I highly recommend.</p>
    		<p>Builds are avalailable for Windows and macOS. An HTML5 and Linux version will be available soon.</p>
    		<ul id="build-list" class="list-group mx-auto mb-4">
          <li class="list-group-item">
          	<a class="download-link" href="https://github.com/enderandpeter/Jack-The-Giant/releases/download/v1/JackTheGiant-win.zip">
          		<img src="resources/jack-the-giant/windows.svg" class="download-icon"/>
          		<span class="download-link">Download for Windows</span>
          	</a>
          </li>
          <li class="list-group-item">
          	<a class="download-link" href="https://github.com/enderandpeter/Jack-The-Giant/releases/download/v1/JackTheGiant-mac.zip">
          		<img src="resources/jack-the-giant/apple.svg" class="download-icon"/>
          		<span class="download-link">Download for macOS</span>
          	</a>
          </li>
        </ul>
        <small>
        		<div>Icons made by <a href="http://www.freepik.com" target="_blank" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        		<div>Icons made by <a href="https://www.flaticon.com/authors/dave-gandy" target="_blank" title="Dave Gandy">Dave Gandy</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        </small>
	</div>
@endsection