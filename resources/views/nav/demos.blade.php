@push('nav-list-items')	
	@if ( Route::current()->uri() === '/' )
		<li class="breadcrumb-item active">Demos</li>
	@else
		<li class="breadcrumb-item"><a href="/">Demos</a></li>
	@endif
@endpush