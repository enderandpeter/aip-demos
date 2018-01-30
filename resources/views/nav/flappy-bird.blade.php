@push('nav-list-items')
	@if ( Route::current()->uri() === 'flappy-bird' )
		<li class="breadcrumb-item active">Yet Another Flappy Bird Clone</li>
	@else
		<li class="breadcrumb-item"><a href="/flappy-bird">Yet Another Flappy Bird Clone</a></li>
	@endif
@endpush