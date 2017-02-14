@extends('layouts.master')

@include('css.bootstrap')
@push('css')
	<link rel="stylesheet" type="text/css" href="css/message-to-mozilla/style.css" />
@endpush

@include('scripts.jquery')
@include('scripts.ko')
@include('scripts.bootstrap')

@push('nav-list-items')	
	@if ( Route::current()->uri() === 'message-to-mozilla' )
		<li class="active">Message to Mozilla</li>
	@else
		<li><a href="/message-to-mozilla">Message to Mozilla</a></li>
	@endif
@endpush

@section('title')
Message to Mozilla
@endsection

@section('body-content')
<div id="main-content">
	<p>Hello Mozilla recruiters. Okay, I am ready to do whatever is necessary to work for you company. I have not seen a more responsive group of 
	people to the best tech communities and individuals contributing to Internet technology today. Firefox is something like a community-driven OS
	that rightly attracts the brightest people, and their security standards from deprecating NPAPI to disallowing innerHTML in addons proves that 
	there are working brains in your ranks.</p>
	
	<p>I am also greatly impressed by your choice of mecurial for version control. It is challenging enough convincing many an established company 
	in my area to simply upgrade from Subversion to git. I am tired of dragging unwilling people up mountains that they know they want to be on top 
	of but refuse to do so until some great catastrophe forces them to.</p>
	
	<p>I am either always the smartest guy in the room among developers who are reluctant to improve how they work, all the while decrying the 
	difficulty of their workflow, or I somehow fail to convince companies with decent technical infrastructure that I am wholly capable of full 
	LAMP stack development. I am much stronger on the back-end than the front-end, but I have a passion for JavaScript, and Douglas Crockford is
	 my mentor. My tech heroes are Linus Torvalds, Mr. Crockford, and James Gosling. I can never find a company that even knows who these people 
	 are, yet they daily rely on technology made with a certain spirit they started that is frequently lost in tech companies.</p>
	
	<p>I live in Colorado and moved to Greenwood Village to be in the so called Denver Tech Center area. The groups here are awash in Subversion 
	and are mostly unfamiliar with continuous integration. The most commonly hiring companies are wealth management, payment processing and 
	investment companies that have been doing things the same way forever and are downright terrified of change.  The story is typically the same:
	they are rightly frustrated with their well-spirited yet rather shoddy homebrewed frameworks and are reluctant to use established 
	ones like Laravel or Phalcon, for instance.</p>
	
	<p>From what I can tell from <a href="http://www.whatcanidoformozilla.org/#!/progornoprog/proglang/php/" target="_blank">What I Can Do for Mozilla</a>, most of your PHP
	work deals with WordPress. I have actually made <a href="https://github.com/enderandpeter/wpgithooks/tree/wpaddons" target="_blank">git hooks</a> for deploying WordPress 
	with WP-CLI using different server names and it even works with multi-site, but too few people in this state understand WordPress enough to be impressed by that.
	Combine a tool like this with <a href="https://versionpress.net/" target="_blank">VersionPress</a>, and you cannot possibly lose.</p>
	
	<p>Here are some online profiles of my experience:</p>
	<ul>
		<li><a href="http://stackoverflow.com/cv/spenceriv" target="_blank">Stack Careers</a></li>
		<li><a href="https://www.linkedin.com/in/spencerwilliamsiv" target="_blank">LinkedIn</a></li>
	</ul>
	
	<p>You can also see some of my demos on <a href="{{ env('APP_URL', 'http://demos.aninternetpresence.net') }}">this site</a>.</p>
	
	<p>I don't have a CS or bachelor's degree, yet I am rather formally trained, and I understand basic discrete math concepts like formal logic,
	 logical equivalencies, and combinatorics. I do not want to go back to college, but I'm really starting to feel like my kind of people are 
	 based there. I refuse to believe that such experiences are necessary to learn what is important to your field of work or study, but if 
	 that's really what it takes to finally be surrounded by serious people, then I may not have a choice. Please, <a href="mailto:spencer@aninternetpresence.net">tell me your thoughts</a>.</p>
</div>	
@endsection