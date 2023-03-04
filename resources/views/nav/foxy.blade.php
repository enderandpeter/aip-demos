@push('nav-list-items')
    @if ( Route::current()->uri() === 'foxy' )
        <li class="breadcrumb-item active">Foxy</li>
    @else
        <li class="breadcrumb-item"><a href="/foxy">Foxy</a></li>
    @endif
@endpush
