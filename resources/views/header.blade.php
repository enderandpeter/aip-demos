<nav id="site-nav">
	<ol class="breadcrumb">
	  <li class="breadcrumb-item"><a href="{{ env('MAIN_URL', 'https://aninternetpresence.net') }}" target="_blank">An Internet Presence</a></li>
	  @stack('nav-list-items')
	</ol>
</nav>