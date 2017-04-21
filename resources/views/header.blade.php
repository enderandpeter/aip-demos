<nav>
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="{{ env('MAIN_URL', 'http://aninternetpresence.net') }}" target="_blank">An Internet Presence</a></li>  
	  @stack('nav-list-items')
	</ol>
</nav>