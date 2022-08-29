<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'AIP Demos') }}</title>

        <!-- Scripts -->
        @routes
        @viteReactRefresh
        @vite('resources/js/search-my-backyard.ts')
        @inertiaHead
    </head>
    <body id="search-my-backyard-body" class="font-sans antialiased d-flex flex-column">
        @push('nav-list-items')
            @if ( Route::current()->uri() === 'search-my-backyard' )
                <li class="breadcrumb-item active">Search my Backyard</li>
            @else
                <li class="breadcrumb-item"><a href="/search-my-backyard">Search my Backyard</a></li>
            @endif
        @endpush
        @include('header')
        @inertia
    </body>
</html>
