@push('css')
	<link rel="stylesheet" type="text/css" href="/css/main.css" />
@endpush

<!DOCTYPE html>
<html lang="en">
<head>
	@stack('css')
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<title>AIP | Demos @hasSection('title') | @yield('title') @else @endif</title>
	@stack('custom-head')
</head>
@yield('body')
</html>