@push('nav-list-items')
	@if ( Route::current()->uri() === 'jack-the-giant-player' )
		<li class="breadcrumb-item active">Jack the Giant - WebGL Player</li>
	@else
		<li class="breadcrumb-item"><a href="/jack-the-giant-player">Jack the Giant - WebGL Player</a></li>
	@endif
@endpush