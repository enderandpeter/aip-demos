@push('nav-list-items')	
	@if ( Route::current()->uri() === '/' )
		<li class="active">Demos</li>
	@else
		<li><a href="/">Demos</a></li>
	@endif
@endpush

<nav>
	<ol class="breadcrumb">
	  <li><a href="{{ env('MAIN_URL', 'http://aninternetpresence.net') }}" target="_blank">An Internet Presence</a></li>  
	  @stack('nav-list-items')
	</ol>
</nav>