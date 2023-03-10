@extends('layouts.foxy')

@include('nav.demos')

@push('css')
    <link rel="stylesheet" type="text/css" href="css/foxy/main.css" />
@endpush

@section('title')
Foxy
@endsection

@section('body-content')
	<div id="main-container" class="container-fluid text-center">
        <div id="screenshot-image-container">
            <img src="{{ config('filesystems.disks.s3.url') . '/images/Foxy01.png' }}" class="screenshot-image" alt="Foxy crouching to avoid bird and slime" class="img-fluid" />
            <div class="second-row d-flex justify-content-center">
                <img src="{{ config('filesystems.disks.s3.url') . '/images/Foxy02.png' }}" class="screenshot-image" alt="Foxy reading a sign about how villagers left" class="img-fluid" />
                <img src="{{ config('filesystems.disks.s3.url') . '/images/Foxy03.png' }}" class="screenshot-image" alt="Foxy demonstrating his dexterity" class="img-fluid" />
            </div>
        </div>
        <p>I made this small demo of a game with Unity, using a large handful of free assets. "Foxy" is a working title. I think this character was meant to be a squirrel, actually.</p>
        <h2>Premise</h2>
        <p>Your forest home has been suddenly corrupted. The villagers have high-tailed it. The best you can do for now is find a way to escape.</p>
        <h2>How To Play</h2>
        <p>Use the arrow keys or WASD to move. Hold the down arrow or S to crouch. To climb ladders, use the up and down arrow keys. Use Space to jump.</p>
        <div id="lessons-learned-container">
            <h2>Lessons Learned</h2>
            <p>
                Creating this game, small as it is, was a major undertaking. Several learning resources were invaluable, which I will be crediting.
                I think these may have been the most important lessons:
                <ul id="lessons-list">
                    <li>Always use the latest <abbr title="Long Term Support">LTS</abbr> version of Unity to avoid so many bugs that have been fixed long ago.</li>
                    <li>Try different audio file types if one type is giving you trouble.</li>
                    <li>If you see jittery animation when using Mechanim, chances are that two animations are trying to play at once. Check the Animator window to confirm. Be sure
                    all unwanted animation states are disabled and only what is wanted is enabled at the time an animation should be played.</li>
                    <li>Sometimes, initializing class properties in Start or Awake is too early, when the property is for a component on another game object. Even if it loads just fine in the Unity Editor, the Unity Player may have a different result.
                        It is more reliable to assign the resource to the object in the editor,
                    or use <a href="https://docs.unity3d.com/ScriptReference/Object.FindObjectOfType.html" target="_blank">FindObjectOfType</a> when it is needed, or other solutions along those lines.</li>
                </ul>
            </p>
        </div>
        <div id="invaluable-resources-container">
            <h3>Learning Resources of Incalculable Value</h3>
            <ul id="invaluable-resources-list">
                <li><a href="https://www.youtube.com/@Brackeys" target="_blank">Brackeys</a></li>
                <li><a href="https://www.youtube.com/@Blackthornprod" target="_blank">Blackthornp</a></li>
                <li><a href="https://www.youtube.com/@codinginflow" target="_blank">Coding In Flow</a></li>
                <li><a href="https://www.youtube.com/@BMoDev" target="_blank">Bmo</a></li>
                <li>Might go without saying, but <a href="https://www.youtube.com/@unity" target="_blank">Unity</a></li>
            </ul>
        </div>
        <p>
            Builds are available for <a href="{{ config('filesystems.disks.s3.url') . '/releases/Foxy/Windows/FoxCrazy_Windows_1_0.zip' }}">Windows</a> and <a href="{{ asset('releases/Foxy/macOS/FoxCrazy_MacOS_Intel_and_Silicon_1_0.zip') }}">macOS</a>. Possibly Linux in the future.
        </p>
        <ul id="build-list" class="list-group mx-auto mb-4">
            <li class="list-group-item">
                <a class="download-link" href="{{ config('filesystems.disks.s3.url') . '/releases/Foxy/Windows/FoxCrazy_Windows_1_0.zip' }}">
                    <img src="resources/software/windows.svg" class="download-icon"/>
                    <span class="download-link">Download for Windows</span>
                </a>
            </li>
            <li class="list-group-item">
                <a class="download-link" href="{{ config('filesystems.disks.s3.url') . '/releases/Foxy/macOS/FoxCrazy_MacOS_Intel_and_Silicon_1_0.zip' }}">
                    <img src="resources/software/apple.svg" class="download-icon"/>
                    <span class="download-link">Download for macOS</span>
                </a>
            </li>
        </ul>
        <div id="credits-container">
            <h2>Credits</h2>
            <h3>Game Design and Programming</h3>
            <p>Spencer Williams IV</p>
            <h3>Music</h3>
            <p><a href="https://freesound.org/people/BloodPixelHero/" target="_blank">BloodPixelHero</a></p>
            <h3>Sounds</h3>
            <p><a href="https://freesound.org/people/skyumori/" target="_blank">skyumori</a></p>
            <p><a href="https://freesound.org/people/soundnimja/" target="_blank">soundnimja</a></p>
            <h4>Images</h4>
            <p><a href="https://assetstore.unity.com/packages/2d/characters/sunny-land-103349" target="_blank">Sunny Land Asset Pack (Ansimuz)</a></p>
            <p><a href="https://opengameart.org/content/breakout-graphics-no-shadow" target="_blank">Breakout graphics (Scribe)</a></p>
            <p><a href="https://assetstore.unity.com/packages/2d/free-2d-mega-pack-177430" target="_blank">2D Mega Pack (Brackeys)</a></p>
        </div>
        <small class="mb-4 d-block"">
            <div>Icons made by <a href="http://www.freepik.com" target="_blank" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
            <div>Icons made by <a href="https://www.flaticon.com/authors/dave-gandy" target="_blank" title="Dave Gandy">Dave Gandy</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
        </small>
	</div>
@endsection
