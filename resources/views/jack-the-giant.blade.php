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
    		<p>This game was created by following a <a href="https://www.udemy.com/make-mobile-games-like-a-pro-using-unity-game-engine/" target="_blank">tutorial on Udemy</a> which I highly recommend.</p>
        <p>Builds are available for <a href="https://github.com/enderandpeter/Jack-The-Giant/releases/download/v1.1/JackTheGiant-win.zip">Windows</a> and <a href="https://github.com/enderandpeter/Jack-The-Giant/releases/download/v1.1/JackTheGiant-mac.zip">macOS</a>.
    		A Linux version will be available soon.
    		</p>
        <ul id="build-list" class="list-group mx-auto mb-4">
          <li class="list-group-item">
          	<a class="download-link" href="https://github.com/enderandpeter/Jack-The-Giant/releases/download/v1.1/JackTheGiant-win.zip">
          		<img src="resources/software/windows.svg" class="download-icon"/>
          		<span class="download-link">Download for Windows</span>
          	</a>
          </li>
          <li class="list-group-item">
          	<a class="download-link" href="https://github.com/enderandpeter/Jack-The-Giant/releases/download/v1.1/JackTheGiant-mac.zip">
          		<img src="resources/software/apple.svg" class="download-icon"/>
          		<span class="download-link">Download for macOS</span>
          	</a>
          </li>
          <li class="list-group-item">
          	<a class="download-link" href="/jack-the-giant-player">
          		<img src="resources/software/html5.svg" class="download-icon"/>
          		<span class="download-link">Play via WebGL</span>
          	</a>
      	    <i
      			class="material-icons text-danger float-right"
      			data-toggle="tooltip"
      			data-placement="top"
      			title="Due to a bug in Unity, the player may consume over 1GB of RAM!">
      				error
      		</i>
          </li>
        </ul>
        <small class="mb-4 d-block"">
        		<div>Icons made by <a href="http://www.freepik.com" target="_blank" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        		<div>Icons made by <a href="https://www.flaticon.com/authors/dave-gandy" target="_blank" title="Dave Gandy">Dave Gandy</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        </small>
	</div>
@endsection
