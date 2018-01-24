@push('nav-list-items')
	@if ( Route::current()->uri() === 'jack-the-giant' )
		<li class="breadcrumb-item active">Jack the Giant</li>
	@else
		<li class="breadcrumb-item"><a href="/jack-the-giant">Jack the Giant</a></li>
	@endif
@endpush