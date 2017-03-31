@push('nav-list-items')	
	@if ( Route::current()->uri() === '/' )
		<li class="active">Demos</li>
	@else
		<li><a href="/">Demos</a></li>
	@endif
@endpush