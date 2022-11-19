<nav class="navbar" id="site-nav" aria-label="breadcrumb">
    <div class="container-fluid">
	<ol class="breadcrumb" style="--bs-breadcrumb-padding-x: 1rem;">
	  <li class="breadcrumb-item"><a href="{{ env('MAIN_URL', 'https://aninternetpresence.net') }}" target="_blank">An Internet Presence</a></li>
	  @stack('nav-list-items')
	</ol>
    </div>
</nav>
